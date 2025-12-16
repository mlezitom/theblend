<?php

namespace App\Services;

use App\Model\Events;
use App\Model\Venues;
use Nette\DI\Container;
use Nette\Http\IResponse;
use Nette\Utils\Json;
use Tracy\Debugger;
use Nette\Database\Explorer;

class GooutApiLoader
{
	/**
	 * @var Events
	 */
	private $eventModel;

	/**
	 * @var Venues
	 */
	private $venueModel;

	/**
	 * @var string
	 */
	private $feedUrl;

	/**
	 * @var Container
	 */
	private $context;

	/**
	 * @var Explorer
	 */
	private $db;

    /**
     * @param Events $eventModel
     * @param Venues $venueModel
     * @param string $feedUrl
     * @param Container $context
     */
    public function __construct(Events $eventModel, Venues $venueModel, $feedUrl, Container $context, Explorer $db)
    {
        $this->eventModel = $eventModel;
        $this->venueModel = $venueModel;
        $this->feedUrl = $feedUrl;
        $this->context = $context;
        $this->db = $db;
    }

    public function load()
	{
		$this->db->beginTransaction();
		try {
			$allData = $this->loadDataFromFeed();

			if ($allData->status != IResponse::S200_OK) {
			    throw new GoOutLoaderException('Unable to load events from feed.');
            }
			$this->db->table('event')->update([
				'visible' => 0,
			]);

            if (isset($allData->venues)) {
			    $this->loadVenues($allData->venues);
            }

            if (isset($allData->schedule)) {
			    $this->loadEvents($allData->schedule);
            }
		    $this->db->commit();
		} catch (\Throwable $e) {
			dump($e);
			$this->db->rollBack();
			return;
		}
	}

	/**
	 * @return array
	 */
	private function loadDataFromFeed()
	{
		$rawData = file_get_contents($this->feedUrl);
		if (!$rawData) {
			return [];
		}
		return Json::decode($rawData);
	}

    /**
     * @param array $events
     */
	private function loadEvents($events)
    {
        foreach ($events as $evtData) {
            $event = $this->eventModel->getByGoOutId($evtData->id);
            if (!$event) {
                $event = $this->eventModel->getTable()->insert([
                    'goout_id' => $evtData->id,
                ]);
            }
            if (isset($evtData->sale->state) && $evtData->sale->state == 'HIDDEN') {
                $event->update([
                    'visible' => 0,
                ]);
            } else {
				$event->update([
					'visible' => 1,
				]);
			}

            $venue = $this->venueModel->getByGoOutId($evtData->venueId);

            $link = '';
            if(isset($evtData->sale)) {
            	$link = $evtData->sale->url;
			} else {
            	$link = $evtData->url;
			}

            $event->update([
                'link_goout' => $link,
                'soldout' => !isset($evtData->sale) || $evtData->sale->state == 'ACTIVE' ? 0 : 1,
                //'visible' => !$evtData->cancelled,
                'event_date' => $evtData->start,
                'venue_id' => $venue ? $venue->id : null,
            ]);
            Debugger::barDump($event->toArray());
        }
    }


    /**
     * @param array
     */
	private function loadVenues($venues)
    {
    	if (!$venues) {
            return;
        }
        foreach($venues as $gooutVenueId => $venueData) {
        	$venue = $this->venueModel->getByGoOutId($gooutVenueId);
            if (!$venue) {
                $venue = $this->venueModel->getTable()->insert([
                    'goout_id' => $gooutVenueId,
                ]);
            }
            $venue->update([
                'name' => $venueData->name,
                'city' => rtrim($venueData->city, '0123456789 '),
                'address' => $venueData->address,
            ]);
        }
    }
}

class GoOutLoaderException extends \Exception {}