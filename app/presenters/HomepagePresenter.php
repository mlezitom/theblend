<?php

namespace App\Presenters;


use App\Model\Events;
use App\Services\GooutApiLoader;
use Nette\Caching\Cache;

class HomepagePresenter extends BasePresenter
{
	/**
	 * @var GooutApiLoader
	 * @inject
	 */
	public $apiLoader;

    /**
     * @var Cache
	 * @inject
     */
	public $cache;

    /**
     * @var Events
     * @inject
     */
	public $eventsModel;

	public function renderDefault()
	{
	    //$this->cacheLoadEvents();
	    $events = $this->eventsModel->getEventsForHomepage();
        $this->template->events = [
            'classic' => array_filter($events, function($event) {
                return !str_contains($event->link_goout, '-premium');
            }),
            'premium' => array_filter($events, function($event) {
                return str_contains($event->link_goout, '-premium');
            }),
        ];
        $this->template->cities = [
            'classic' => array_unique(array_map(function($event) {
                return $event->city;
            }, $this->template->events['classic'])),
            'premium' => array_unique(array_map(function($event) {
                return $event->city;
            }, $this->template->events['premium'])),
        ];
	}

	public function render2025()
	{

	}

	public function actionImport()
	{
		$this->apiLoader->load();
		$this->terminate();
	}

	public function renderB2b()
	{

	}

	private function cacheLoadEvents()
    {
        if (!$this->cache->load('gooutEvents')) {
            $this->apiLoader->load();
            $this->cache->save('gooutEvents', true, [
	            [Cache::EXPIRE => 15 * 60],
            ]);
        }
    }
}
