<?php

Class Core_Session extends Core_Object
{
	public $id = '';
	public $data = '';
	public $agent = '';
	public $ipaddress = '';
	public $hash = '';
	public $userid = 0;
	public $controller = '';
	public $action = '';
	public $datecreated = 0;
	public $dateexpired = 0;
	
	public $actor = null;	// relationship with table `lit_ac_user`
	public $browser = null;	// Browser object base on browser search
	
    public function __construct($id = '')
	{
		parent::__construct();
    
		if(strlen($id) > 0)
			$this->getData($id);
	}
    
	
   
	/**
	 * Get the object data base on primary key
	 * @param int $id : the primary key value for searching record.
	 */
	public function getData($id)
	{
		$id = (string)$id;
		$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'sess s
				LEFT JOIN ' . TABLE_PREFIX . 'ac_user u ON s.s_userid = u.u_id
				WHERE s.s_id = ?';
		$row = $this->db->query($sql, array($id))->fetch(); 

		$this->id = $row['s_id'];     
		$this->data = $row['s_data'];
		$this->agent = $row['s_agent'];
		$this->ipaddress = long2ip($row['s_ipaddress']);
		$this->hash = $row['s_hash'];
		$this->userid = $row['s_userid'];
		$this->controller = $row['s_controller'];
		$this->action = $row['s_action'];
		$this->datecreated = $row['s_datecreated'];
		$this->dateexpired = $row['s_dateexpired'];
		
		$this->browser = new browser($this->agent);
		          
		$this->actor = new Core_User();
		$this->actor->initMainInfo($row);   
		        
	}

	/**
	 * Delete current object from database, base on primary key
	 *
	 * @return int the number of deleted rows (in this case, if success is 1)
	 */
	public function delete()
	{
		$sql = 'DELETE FROM ' . TABLE_PREFIX . 'sess WHERE s_id = ?';
		$rowCount = $this->db->query($sql, array($this->id))->rowCount();

		return $rowCount;
	}
    
    /**
     * Count the record in the table base on condition in $where
	 *
	 * @param string $where: the WHERE condition in SQL string.
	 * @param boolean $getUserDetail: flag to join with table lit_ac_user or not, for performance when joining too many tables.
     */
	public static function countList($where, $getUserDetail = false, $isDistinct = false)
	{
		global $db;

		if($isDistinct)
			$sql = 'SELECT COUNT(DISTINCT s_ipaddress) FROM ' . TABLE_PREFIX . 'sess s';
		else
			$sql = 'SELECT COUNT(*) FROM ' . TABLE_PREFIX . 'sess s';
        		
		if($getUserDetail)
			$sql .= ' LEFT JOIN ' . TABLE_PREFIX . 'ac_user u ON s.s_userid = u.u_id';
        
		if($where != '')
			$sql .= ' WHERE ' . $where;

		return $db->query($sql)->fetchSingle();
	}

	/**
	 * Get the record in the table with paginating and filtering
	 *
	 * @param string $where the WHERE condition in SQL string
	 * @param string $order the ORDER in SQL string
	 * @param string $limit the LIMIT in SQL string
	 * @param boolean $getUserDetail: flag to join with table lit_ac_user or not, for performance when joining too many tables.
	 */
	public static function getList($where, $order, $limit = '', $getUserDetail = false, $isDistinct = false)
	{
		global $db;

		$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'sess s';

		if($getUserDetail)
			$sql .= ' LEFT JOIN ' . TABLE_PREFIX . 'ac_user u ON s.s_userid = u.u_id';

		if($where != '')
			$sql .= ' WHERE ' . $where;
			
		if($isDistinct)
			$sql .= ' GROUP BY s_ipaddress ';

		if($order != '')
			$sql .= ' ORDER BY ' . $order;

		if($limit != '')
			$sql .= ' LIMIT ' . $limit;
			
		$outputList = array();
		$stmt = $db->query($sql);
		while($row = $stmt->fetch())
		{
			$mySession = new Core_Session();
			$mySession->id = $row['s_id'];     
			$mySession->data = $row['s_data'];
			$mySession->agent = $row['s_agent'];
			$mySession->ipaddress = long2ip($row['s_ipaddress']);
			$mySession->hash = $row['s_hash'];
			$mySession->userid = $row['s_userid'];
			$mySession->controller = $row['s_controller'];
			$mySession->action = $row['s_action'];
			$mySession->datecreated = $row['s_datecreated'];
			$mySession->dateexpired = $row['s_dateexpired'];
			$mySession->browser = new browser($mySession->agent);
			if($getUserDetail)
			{
				$mySession->actor = new Core_User();
				$mySession->actor->initMainInfo($row);
			}       
            $outputList[] = $mySession;
        }
        return $outputList;
    }
   
	/**
	 * Select the record, Interface with the outside (Controller)
	 *
	 * @param array $formData : filter array to build WHERE condition
	 * @param string $sortby : indicating the order of select
	 * @param string $sorttype : DESC or ASC
	 * @param string $limit: the limit string, offset for LIMIT in SQL string
	 * @param boolean $countOnly: flag to counting or return datalist
	 * @param boolean $getUserDetail: flag to join with table lit_ac_user or not, for performance when joining too many tables.
	 * 
	 */
	public static function getSessions($formData, $sortby, $sorttype, $limit = '', $countOnly = false, $getUserDetail = false, $isDistinct = false)
	{
		$whereString = '';
		
		if($formData['fsession'] > 0)
			$whereString .= ($whereString != '' ? ' AND ' : '') . 's.s_id = "'.Helper::plaintext($formData['fsession']).'" ';
		
		if($formData['fuserid'] > 0)
			$whereString .= ($whereString != '' ? ' AND ' : '') . 's.s_userid = '.(int)$formData['fuserid'].' ';
		
		if($formData['fip'] > 0)
			$whereString .= ($whereString != '' ? ' AND ' : '') . 's.s_ipaddress = '.ip2long($formData['fip']).' ';
		
		if(strlen($formData['fkeywordFilter']) > 0)
		{
			$formData['fkeywordFilter'] = Helper::plaintext($formData['fkeywordFilter']);
			
			if($formData['fsearchKeywordIn'] == 'title')
				$whereString .= ($whereString != '' ? ' AND ' : '') . 'e.e_title LIKE \'%'.$formData['fkeywordFilter'].'%\'';
			else
			{
				$whereString .= ($whereString != '' ? ' AND ' : '') . ' (e.e_title LIKE \'%'.$formData['fkeywordFilter'].'%\' OR  e.e_description LIKE \'%'.$formData['fkeywordFilter'].'%\')';
			}
		}
		
		//checking sort by & sort type
		if($sorttype != 'DESC' && $sorttype != 'ASC')
			$sorttype = 'DESC';
			
		if($sortby == 'dateexpired')
			$orderString = ' s.s_dateexpired ' . $sorttype;    
		elseif($sortby == 'ip')
			$orderString = ' s.s_ipaddress ' . $sorttype;    
		elseif($sortby == 'agent')
			$orderString = ' s.s_agent ' . $sorttype;    
		elseif($sortby == 'controller')
			$orderString = ' s.s_controller ' . $sorttype;    
		elseif($sortby == 'userid')
			$orderString = ' s.s_userid ' . $sorttype;    
		else
			$orderString = ' e.e_id ' . $sorttype;   
			
		if($countOnly)
			return self::countList($whereString, $getUserDetail, $isDistinct);
		else
			return self::getList($whereString, $orderString, $limit, $getUserDetail, $isDistinct);
	}
	
	public function isExpired()
	{
		return $this->dateexpired < time();
	}

	public static function countmemberonline()
	{
		global $db;
		
		$sql = 'SELECT COUNT(*) FROM ' . TABLE_PREFIX . 'sess
				WHERE s_userid <> 0';
		return $db->query($sql)->fetchSingle();
	}
   
}