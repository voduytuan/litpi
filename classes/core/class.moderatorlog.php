<?php

Class Core_ModeratorLog extends Core_Object
{
	public $uid = 0;
	public $email = '';
	public $id = 0;
	public $ipaddress = 0;
	public $type = '';
	public $mainid = 0;
	public $moredata = array();
	public $datecreated = 0;
	
	public function __construct($id = 0)
	{
		parent::__construct();
		
		if($id > 0)
			$this->getData($id);
	}
	
	public function addData()
	{
		$this->datecreated = time();
		$this->ipaddress = Helper::getIpAddress(true);
		
		$sql = 'INSERT INTO ' . TABLE_PREFIX . 'moderator_log(
					u_id,
					u_email,
					ml_ipaddress, 
					ml_type, 
					ml_mainid, 
					ml_serialized_data, 
					ml_datecreated)
				VALUES(?, ?, ?, ?, ?, ?, ?)';
				
		$rowCount = $this->db->query($sql, array(
		    	(int)$this->uid, 
		    	(string)$this->email, 
		    	(int)$this->ipaddress, 
		    	(string)$this->type, 
		    	(int)$this->mainid, 
		    	(string)serialize($this->moredata), 
		    	(int)$this->datecreated
			))->rowCount();
			
		$this->id = $this->db->lastInsertId();
		
		return $this->id;
	}
	
	public function delete()
	{
        $sql = 'DELETE FROM ' . TABLE_PREFIX . 'moderator_log
        		WHERE ml_id = ? ';
		$rowCount = $this->db->query($sql, array($this->id))->rowCount();
		
		return $rowCount;
	}
	
	public static function clear()
	{
		global $db;
		$sql = 'TRUNCATE TABLE ' . TABLE_PREFIX .'moderator_log';
		return $db->query($sql);
	}
	
	private function getData($id)
	{
		$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'moderator_log ml
				WHERE ml_id = ?';
		$row = $this->db->query($sql, array((int)$id))->fetch();
		$this->uid = $row['u_id'];
		$this->email = $row['u_email'];
		$this->id = $row['ml_id'];
		$this->ipaddress = long2ip($row['ml_ipaddress']);
		$this->type = $row['ml_type'];
		$this->mainid = $row['ml_mainid'];
		$this->moredata = unserialize($row['ml_serialized_data']);
		$this->datecreated = $row['ml_datecreated'];
	}
	
	public static function countList($where)
	{
		global $db;
		$sql = 'SELECT COUNT(*) FROM ' . TABLE_PREFIX . 'moderator_log ml';
		
		if($where != '')
			$sql .= ' WHERE ' . $where;
			
		return $db->query($sql)->fetchSingle();
	}
	
	public static function getList($where, $order , $limit = '')
	{
		global $db;
		$outputList = array();
		$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'moderator_log ml';
		
		if($where != '')
			$sql .= ' WHERE ' . $where;
			
		if($order != '')
			$sql .= ' ORDER BY ' . $order;
				
		if($limit != '')
			$sql .= ' LIMIT ' . $limit; 
				
		$stmt = $db->query($sql);
		while($row = $stmt->fetch())
		{
			$myLog = new Core_ModeratorLog();
			$myLog->uid = $row['u_id'];
			$myLog->email = $row['u_email'];
			$myLog->id = $row['ml_id'];
			$myLog->ipaddress = long2ip($row['ml_ipaddress']);
			$myLog->type = $row['ml_type'];
			$myLog->mainid = $row['ml_mainid'];
			$myLog->moredata = unserialize($row['ml_serialized_data']);
			$myLog->datecreated = $row['ml_datecreated'];
			$outputList[] = $myLog;
		}
		return $outputList;
	}
	
	public static function getLogs($formData, $sortby, $sorttype, $limit = '', $countOnly = false)
	{
		$whereString = '';
		
		if($formData['fuid'] > 0)
			$whereString .= ($whereString != '' ? ' AND ' : '') . 'ml.u_id = '.(int)$formData['fuid'].' ';
		
		if(strlen($formData['ftype']) > 0)
			$whereString .= ($whereString != '' ? ' AND ' : '') . 'ml.ml_type LIKE \'%'.$formData['ftype'].'%\' ';
		
		if(strlen($formData['fip']) > 0)
			$whereString .= ($whereString != '' ? ' AND ' : '') . 'ml.ml_ipaddress = '.ip2long($formData['fip']).' ';
		
		
		//checking sort by & sort type
		if($sorttype != 'DESC' && $sorttype != 'ASC')
			$sorttype = 'DESC';
			
		if($sortby == 'type')
			$orderString = ' ml.ml_type ' . $sorttype;    
		else
			$orderString = ' ml.ml_id ' . $sorttype;            
			
			
		if($countOnly)
			return self::countList($whereString);
		else
			return self::getList($whereString, $orderString, $limit);
	}
	
}


?>