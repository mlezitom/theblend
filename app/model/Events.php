<?php
/**
 * Created by PhpStorm.
 * User: tomas
 * Date: 21.10.2017
 * Time: 11:31
 */

namespace App\Model;


use Nette\Database\Context;

class Events
{
    public $tableName = 'event';

    /**
     * @var Context
     */
    public $db;

    /**
     * Events constructor.
     * @param Context $db
     */
    public function __construct(Context $db)
    {
        $this->db = $db;
    }

    /**
     * @return \Nette\Database\Table\Selection
     */
    public function getTable() {
        return $this->db->table($this->tableName);
    }

    /**
     * @param $id
     * @return \Nette\Database\Table\IRow
     */
    public function get($id) {
        return $this->getTable()->get($id);
    }

    /**
     * @param int $goOutId
     * @return \Nette\Database\Table\IRow
     */
    public function getByGoOutId($goOutId) {
        return $this->getTable()->where('goout_id', $goOutId)->fetch();
    }

	/**
	 * @return int
	 */
    public function truncate()
	{
		return $this->getTable()->delete();
	}

	public function getEventsForHomepage()
    {
        $sql = 'SELECT e.id AS event_id, e.link_goout, e.soldout, e.event_date, v.name AS venue_name, v.city, v.address
            FROM event e JOIN venue v ON v.id = e.venue_id
            WHERE event_date >= CURRENT_DATE
            AND e.visible = 1
            ORDER BY event_date ASC, city ASC'
        ;
        return $this->db->query($sql)->fetchAll();
    }

	public function getGridDatasource()
    {
        $sql = 'SELECT e.id, e.link_goout, e.soldout, e.event_date, v.name AS venue_name, v.city, v.address, e.visible
            FROM event e JOIN venue v ON v.id = e.venue_id
            ORDER BY event_date ASC'
        ;
        return $this->db->query($sql)->fetchAll();
    }
}
