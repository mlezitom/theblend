<?php
/**
 * Created by PhpStorm.
 * User: tomas
 * Date: 21.10.2017
 * Time: 11:31
 */

namespace App\Model;


use Nette\Database\Context;

class Venues
{
    public $tableName = 'venue';

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
}
