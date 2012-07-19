<?php

Class Core_User extends Core_Object
{
	public $id = 0;
	public $fullname = '';
	public $groupid = 0;
	
	public $email = '';
	public $password = '';
	public $datecreated = 0;
	public $datemodified = 0;
	
	public $newpass = '';
	public $sessionid = '';
	public $userpath = '';
	
	public function __construct($id = 0, $loadFromCache = false)
	{
		parent::__construct();
		$this->sessionid = session_id();
		
		if($id > 0)
		{
			$this->getData($id);
				
		}
	}
	
	public function checkPerm()
	{
		global $registry, $groupPermisson, $smarty;       
		
		//echo $GLOBALS['controller_group'] . '<br />';
		//echo $GLOBALS['controller'] . '<br />';
		//echo $GLOBALS['action'] . '<br />';
		//echo $this->groupid . '<br />';
		
		$this->groupid = (int)$this->groupid;
		
		//print_r($groupPermisson[$this->groupid][$GLOBALS['controller_group']]);
		//var_dump(!in_array($GLOBALS['controller'].'_*', $groupPermisson[$this->groupid][$GLOBALS['controller_group']]));
		//print_r($this);
		
		if(!isset($groupPermisson[$this->groupid][$GLOBALS['controller_group']]) || (!in_array($GLOBALS['controller'].'_'.$GLOBALS['action'], $groupPermisson[$this->groupid][$GLOBALS['controller_group']]) && !in_array($GLOBALS['controller'].'_*', $groupPermisson[$this->groupid][$GLOBALS['controller_group']])))
		{    
			if($GLOBALS['controller_group'] == 'admin')
			{
				//if not login
				if($this->id == 0)
				{
					$redirectUrl = $registry->conf['rooturl_admin'];
					
					header('location: '.$registry->conf['rooturl'].'site/login?refer=1&redirect=' . base64_encode($redirectUrl));
					exit();	
				}
			}
			
			
		
			header('location: ' . $registry->conf['rooturl'] . 'site/notpermission');
			exit();
		}
	}
	
	/**
	* Lay thong tin user tu session (danh cho user da login hoac su dung remember me
	* 
	*/
	public function updateFromSession()
	{
		global $setting;
		
		if(isset($_SESSION['userLogin']) && $_SESSION['userLogin'] > 0)
		{
			$this->getData($_SESSION['userLogin']);
		}
		else
		{
			//"remember me" function
			if(isset($_COOKIE['myHashing']) && strlen($_COOKIE['myHashing']) > 0)
			{
				$cookieRememberMeInfo = viephpHashing::cookiehasingParser($_COOKIE['myHashing']);
				
				
				$this->getData($cookieRememberMeInfo['userid']);
				
								
				
				if(viephpHashing::authenticateCookiehashing($cookieRememberMeInfo['shortPasswordString'], $this->password))
				{
					session_regenerate_id(true);
					
					$_SESSION['userLogin'] = $this->id;
					$_SESSION['loginauto'] = 1;
					
					
					
				}
			}//end remember me
		}
	}
	
	public function addData()
	{	
		$this->datecreated = time();
		
		$sql = 'INSERT INTO ' . TABLE_PREFIX . 'ac_user (u_fullname, u_groupid)
				VALUES(?, ?)';
		$this->db->query($sql, array( 
			(string)$this->fullname, 
			(int)$this->groupid, 
			));
			
			
		$this->id = $this->db->lastInsertId();
		
		if($this->id > 0)
		{
			$sql = 'INSERT INTO ' . TABLE_PREFIX . 'ac_user_profile (
						u_id, 
						up_email,
						up_password, 
						up_datecreated
						)
					VALUES(?, ?, ?, ?)';
			$this->db->query($sql, array(
		    		(int)$this->id, 
		    		(string)$this->email, 
		    		(string)$this->password, 
		    		(int)$this->datecreated
				));	
				
		
		}
		
		return $this->id;
	}
	
	public function updateData($moreFields = array(), &$error = array())
	{                          
		$this->datemodified = time();
			
		if(
			(isset($moreFields['fullname']) && strcmp($this->fullname, $moreFields['fullname']) != 0) || 
			(isset($moreFields['groupid']) && $this->groupid != $moreFields['groupid'])
		)
		{
				
			if(isset($moreFields['fullname']))
				$this->fullname = $moreFields['fullname'];
				
			if(isset($moreFields['groupid']))
				$this->groupid = (int)$moreFields['groupid'];
			
			
			
			$sql = 'UPDATE ' . TABLE_PREFIX . 'ac_user
					SET u_fullname = ?,
						u_groupid = ?
					WHERE u_id = ?
					LIMIT 1';
			$this->db->query($sql, array(
				(string)$this->fullname, 
				(int)$this->groupid,
				$this->id));
		}
		
		$moreupdate = '';
		if(strlen($this->newpass) > 0)
			$moreupdate = 'up_password = "'.viephpHashing::hash($this->newpass).'" ,';
			
		//update profile table	
		$sql = 'UPDATE ' . TABLE_PREFIX . 'ac_user_profile
        		SET '.$moreupdate.'
        			up_email = ?,
        			up_datemodified = ? 
        		WHERE u_id = ?'; 
        		
		$stmt = $this->db->query($sql, array(
		    (string)$this->email, 
		    (int)$this->datemodified, 
		    (int)$this->id	
		));
			
		
				
		if($stmt->rowCount() > 0)
			return true;
		else
			return false;
		
	}
	
	
	
	public function getData($id)
	{
		global $registry;
		$id = (int)$id;
		
		$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'ac_user u
				INNER JOIN ' . TABLE_PREFIX . 'ac_user_profile up ON u.u_id = up.u_id
				WHERE u.u_id = ?';
		$row = $this->db->query($sql, array($id))->fetch();
		$this->id = $row['u_id'];
		$this->fullname = $row['u_fullname'];
		$this->groupid = $row['u_groupid'];
		$this->email = $row['up_email'];
		$this->password = $row['up_password'];
		$this->datecreated = $row['up_datecreated'];
		$this->datemodified = $row['up_datemodified'];
		
	}
	
	public function cloneObject(Core_User $myUser)
	{
		$this->id = $myUser->id;
		$this->fullname = $myUser->fullname;
		$this->groupid = $myUser->groupid;
		$this->email = $myUser->email;
		$this->password = $myUser->password;
		$this->datecreated = $myUser->datecreated;
		$this->datemodified = $myUser->datemodified;
	}
	
	
	
	public static function getByEmail($email)
	{
		global $db;
		$myUser = new Core_User();
		if(Helper::ValidatedEmail($email))
		{
			$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'ac_user u
					INNER JOIN ' . TABLE_PREFIX . 'ac_user_profile up ON u.u_id = up.u_id
					WHERE up_email = ?
					LIMIT 1';
			$row = $db->query($sql, array($email))->fetch();
			if($row['u_id'] > 0)
			{
				$myUser->id = $row['u_id'];
				$myUser->fullname = $row['u_fullname'];
				$myUser->groupid = $row['u_groupid'];
				$myUser->email = $row['up_email'];
				$myUser->password = $row['up_password'];
				$myUser->datecreated = $row['up_datecreated'];
				$myUser->datemodified = $row['up_datemodified'];
			}
			
		}
		
		return $myUser;
	}
	
		
	public function delete()
	{
		$sql = 'DELETE FROM ' . TABLE_PREFIX . 'ac_user
        		WHERE u_id = ?	';
		$this->db->query($sql, array($this->id));
		
		$sql = 'DELETE FROM ' . TABLE_PREFIX . 'ac_user_profile
        		WHERE u_id = ?';
		$this->db->query($sql, array($this->id));
		
		
		return true;
	}
	
	/**
	* Gan cac gia tri chinh cho 1 tai khoan user
	* Thuong su dung khi JOIN voi cac chuc nang khac va gan data chinh (id, fullname) vao actor cho cac model khac
	* 
	* @param array $info
	*/
	public function initMainInfo($info = array())
	{
		$this->id = isset($info['u_id']) ? $info['u_id'] : 0;
		$this->fullname = isset($info['u_fullname']) ? $info['u_fullname'] : '';
		$this->groupid = isset($info['u_groupid']) ? $info['u_groupid'] : '';
		
	}
	
	public function getByArray($info = array())
	{
		$this->id = $info['u_id'];
		$this->fullname = $info['u_fullname'];
		$this->groupid = $info['u_groupid'];
		
		$this->email = $info['up_email'];
		$this->password = $info['up_password'];
		$this->datecreated = $info['up_datecreated'];
		$this->datemodified = $info['up_datemodified'];
	}
	
	
	public function writelog($type, $mainid = 0, $arraymoredata = array())
	{
		$myLog = new Core_ModeratorLog();
		$myLog->uid = $this->id;
		$myLog->email = $this->email;
		$myLog->type = $type;
		$myLog->mainid = $mainid;
		$myLog->moredata = $arraymoredata;
		$myLog->addData();
	}
	
	public static function groupname($groupid)
	{	
		if($groupid == GROUPID_ADMIN)
			$groupname = 'Administrator';
		elseif($groupid == GROUPID_MODERATOR)
			$groupname = 'Moderator';
		elseif($groupid == GROUPID_MEMBER)
			$groupname = 'Member';
		elseif($groupid == GROUPID_GUEST)
			$groupname = 'Guest';
			
		return $groupname;
	}
	
	public static function getGroupnameList()
	{
		$groupnameList = array();
		
		$groupnameList[GROUPID_ADMIN] = 'Administrator';
		$groupnameList[GROUPID_MODERATOR] = 'Moderator';
		$groupnameList[GROUPID_MEMBER] = 'Member';
		
		return $groupnameList;
	}
	
	public static function countList($where, $joinString)
	{
		global $db;
		$sql = 'SELECT COUNT(*) FROM ' . TABLE_PREFIX . 'ac_user u
				'.$joinString.'';
		
		if($where != '')
			$sql .= ' WHERE ' . $where;
			
		return $db->query($sql)->fetchSingle();
	}
	
	public static function getList($where, $joinString, $order , $limit = '')
	{
		global $db;
		$outputList = array();
		$sql = 'SELECT * FROM ' . TABLE_PREFIX . 'ac_user u
				'.$joinString.'';
				
		if($where != '')
			$sql .= ' WHERE ' . $where;
			
		if($order != '')
			$sql .= ' ORDER BY ' . $order;
				
		if($limit != '')
			$sql .= ' LIMIT ' . $limit;
			
		$stmt = $db->query($sql);
		while($row = $stmt->fetch())
		{
			$myUser = new Core_User();
			$myUser->id = $row['u_id'];
			$myUser->fullname = $row['u_fullname'];
			$myUser->groupid = $row['u_groupid'];
			$myUser->email = $row['up_email'];
			$myUser->password = $row['up_password'];
			$myUser->datecreated = $row['up_datecreated'];
			$myUser->datemodified = $row['up_datemodified'];
			$outputList[] = $myUser;
		}
		return $outputList;
	}
	
	public static function getUsers($formData, $sortby = 'id', $sorttype = 'DESC', $limit = '', $countOnly = false, $getUserDetail = true)
	{
		$whereString = '';
		$joinString = '';
		
		if($getUserDetail)
			$joinString = 'INNER JOIN ' . TABLE_PREFIX . 'ac_user_profile up ON u.u_id = up.u_id';
		
		
		if($formData['fid'] > 0)
			$whereString .= ($whereString != '' ? ' AND ' : '') . 'u.u_id = '.(int)$formData['fid'].' ';
			
		if(count($formData['femaillist']) > 0)
			$whereString .= ($whereString != '' ? ' AND ' : '') . 'up.up_email IN ( '.implode(',', $formData['femaillist']).') ';
		
		if(count($formData['fidlist']) > 0)
			$whereString .= ($whereString != '' ? ' AND ' : '') . 'u.u_id IN ( '.implode(',', $formData['fidlist']).') ';
		
		
		if($formData['fgroupid'] > 0)
			$whereString .= ($whereString != '' ? ' AND ' : '') . 'u.u_groupid = '.(int)$formData['fgroupid'].' ';
			
		
		if(strlen($formData['fkeywordFilter']) > 0)
		{
			
			$formData['fkeywordFilter'] = preg_replace('/[~!#$%^&*;,?:\'"]/', '', $formData['fkeywordFilter']);
			
			if($formData['fsearchKeywordIn'] == 'email')
				$whereString .= ($whereString != '' ? ' AND ' : '') . 'up.up_email LIKE \'%'.$formData['fkeywordFilter'].'%\'';
			elseif($formData['fsearchKeywordIn'] == 'fullname')
				$whereString .= ($whereString != '' ? ' AND ' : '') . 'u.u_fullname LIKE \'%'.$formData['fkeywordFilter'].'%\'';
			else
				$whereString .= ($whereString != '' ? ' AND ' : '') . '( (up.up_email LIKE \'%'.$formData['fkeywordFilter'].'%\') OR (u.u_fullname LIKE \'%'.$formData['fkeywordFilter'].'%\') )';
		}
		
		//checking sort by & sort type
		if($sorttype != 'DESC' && $sorttype != 'ASC')
			$sorttype = 'DESC';
			
		if($sortby == 'email')
			$orderString = ' up.up_email ' . $sorttype;    
		elseif($sortby == 'group')
			$orderString = ' u.u_groupid ' . $sorttype;      
		else
			$orderString = ' u.u_id ' . $sorttype;   
		
		if($countOnly)
			return self::countList($whereString, $joinString);
		else
			return self::getList($whereString, $joinString, $orderString, $limit);
	}
	
	
	
	/**
	* Kiem tra user nay co phai la group nay ko
	* 
	* Dua vao name
	* $groupname: administrator, moderator, member, membervip, memberbanned, bookstore, publisher, guest
	* 
	* @param mixed $groupname
	*/
	public function isGroup($groupname)
	{
		if($groupname == 'administrator')
			return $this->groupid == GROUPID_ADMIN;
		elseif($groupname == 'member')
			return $this->groupid == GROUPID_MEMBER;
		elseif($groupname == 'guest')
			return $this->groupid == GROUPID_GUEST;
		else
			return false;
		
	}
	
	
	
}


?>
