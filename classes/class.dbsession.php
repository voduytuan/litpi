<?php

/**
 *  A class to handle sessions by using a mySQL database for session related data storage providing better
 *  security then the default session handler used by PHP.
 *
 *  To prevent session hijacking, don't forget to use the {@link regenerate_id} method whenever you do a
 *  privilege change in your application
 *
 *  <i>Before usage, make sure you use the session_data.sql file from the <b>install</b> folder to set up the table
 *  used by the class</i>
 *
 *  After instantiating the class, use sessions as you would normally
 *
 *  This class is an adaptation of John Herren's code from the "Trick out your session handler" article
 *  ({@link http://devzone.zend.com/node/view/id/141}) and Chris Shiflett's code from Chapter 8, Shared Hosting - Pg 78-80,
 *  of his book - "Essential PHP Security" ({@link http://phpsecurity.org/code/ch08-2})
 *
 *  <i>Note that the class assumes that there is an active connection to a mySQL database and it does not attempt to create
 *  one. This is due to the fact that, usually, there is a config file that holds the database connection related
 *  information and another class, or function that handles database connection. If this is not how you do it, you can
 *  easily adapt the code by putting the database connection related code in the "open" method of the class.</i>
 *
 *  This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 2.5 License.
 *  To view a copy of this license, visit {@link http://creativecommons.org/licenses/by-nc-nd/2.5/} or send a letter to
 *  Creative Commons, 543 Howard Street, 5th Floor, San Francisco, California, 94105, USA.
 *
 *  For more resources visit {@link http://stefangabos.blogspot.com}
 *
 *  @author	 Stefan Gabos <ix@nivelzero.ro>
 *  @version	1.0.1 (last revision: August 11, 2006)
 *  @copyright  (c) 2006 Stefan Gabos
 *  @package	dbSession
*/


class dbSession
{

	/**
	 *  Constructor of class
	 *
	 *  Initializes the class and starts a new session
	 *
	 *  There is no need to call start_session() after instantiating this class
	 * 
	 *
	 *  @param 	pdo			$pdoDb				The pdo object, used to query database 
	 *  @param  integer	 $gc_maxlifetime	 the number of seconds after which data will be seen as 'garbage' and
	 *										  cleaned up on the next run of the gc (garbage collection) routine
	 *
	 *										  Default is specified in php.ini file
	 *
	 *  @param  integer	 $gc_probability	 used in conjunction with gc_divisor, is used to manage probability that
	 *										  the gc routine is started. the probability is expressed by the formula
	 *
	 *										  probability = $gc_probability / $gc_divisor
	 *
	 *										  So if $gc_probability is 1 and $gc_divisor is 100 means that there is
	 *										  a 1% chance the the gc routine will be called on each request
	 *
	 *										  Default is specified in php.ini file
	 *
	 *  @param  integer	 $gc_divisor		 used in conjunction with gc_probability, is used to manage probability
	 *										  that the gc routine is started. the probability is expressed by the formula
	 *
	 *										  probability = $gc_probability / $gc_divisor
	 *
	 *										  So if $gc_probability is 1 and $gc_divisor is 100 means that there is
	 *										  a 1% chance the the gc routine will be called on each request
	 *
	 *										  Default is specified in php.ini file
	 *
	 *  @return void
	 */
	private $db = '';
	
	function dbSession($pdoDb, $gc_maxlifetime = '', $gc_probability = '', $gc_divisor = '')
	{
		$this->db = $pdoDb;
		
		// if $gc_maxlifetime is specified and is an integer number
		if ($gc_maxlifetime != '' && is_integer($gc_maxlifetime)) 
		{
			// set the new value
			@ini_set('session.gc_maxlifetime', $gc_maxlifetime);
		}

		// if $gc_probability is specified and is an integer number
		if ($gc_probability != '' && is_integer($gc_probability)) 
		{
			// set the new value
			@ini_set('session.gc_probability', $gc_probability);
		}

		// if $gc_divisor is specified and is an integer number
		if ($gc_divisor != '' && is_integer($gc_divisor)) 
		{
			// set the new value
			@ini_set('session.gc_divisor', $gc_divisor);
		}
		 
		// get session lifetime
		$this->sessionLifetime = ini_get('session.gc_maxlifetime');
		
		// register the new handler
		session_set_save_handler(
			array(&$this, 'open'),
			array(&$this, 'close'),
			array(&$this, 'read'),
			array(&$this, 'write'),
			array(&$this, 'destroy'),
			array(&$this, 'gc')
		);
		register_shutdown_function('session_write_close');
		
		// start the session
		session_start();	
		
		
		global $setting;
				
		$moreUpdate = '';
		$controllerString = trim($GLOBALS['controller_group']) . '_' . trim($GLOBALS['controller']);
		if(isset($setting['site']['sessionExcludeController']))
		{
			if(!in_array($controllerString, $setting['site']['sessionExcludeController']))
			{
				$moreUpdate = 's_controller = "'.$controllerString.'", s_action = "'.$GLOBALS['action'].'",';
			}
		}
		
		// update the existing session's data
		// and set new expiry time
		$sql = 'UPDATE ' . TABLE_PREFIX . 'sess
				SET '.$moreUpdate.'
					s_dateexpired = '.(time() + $this->sessionLifetime).'
				WHERE s_id = "'. session_id().'"
				LIMIT 1';
		$this->db->query($sql, array());
		
			
		
	}
	
	/**
	 *  Deletes all data related to the session
	 *
	 *  @return void
	 */		  
	function stop()
	{
		$this->regenerate_id();
		session_unset();
		session_destroy();
	}
	
	/**
	 *  Regenerates the session id.
	 *
	 *  <b>Call this method whenever you do a privilege change!</b>
	 *
	 *  @return void
	 */
	function regenerate_id()
	{

		// saves the old session's id
		$oldSessionID = session_id();
		
		// regenerates the id
		// this function will create a new session, with a new id and containing the data from the old session
		// but will not delete the old session
		session_regenerate_id();
		
		// because the session_regenerate_id() function does not delete the old session,
		// we have to delete it manually
		$this->destroy($oldSessionID);
		
	}
	
	/**
	 *  Get the number of online users
	 *
	 *  This is not 100% accurate. It depends on how often the garbage collector is run
	 *
	 *  @return integer	 approximate number of users currently online
	 */
	function get_users_online()
	{
		$sql = 'SELECT COUNT(*) 
				FROM ' . TABLE_PREFIX . 'sess
				WHERE s_dateexpired > ?
				';
		return $this->db->query($sql, array(time()))->fetchSingle();
	}
	
	/**
	 *  Custom open() function
	 *
	 *  @access private
	 */
	function open($save_path, $session_name)
	{
		return true;
	}
	
	/**
	 *  Custom close() function
	 *
	 *  @access private
	 */
	function close()
	{
		return true;
	}
	
	/**
	 *  Custom read() function
	 *
	 *  @access private
	 */
	function read($session_id)
	{
		// reads session data associated with the session id
		// but only if the HTTP_USER_AGENT is the same as the one who had previously written to this session
		// and if session has not expired
		$sql = 'SELECT s_data 
				FROM ' . TABLE_PREFIX . 'sess
				WHERE s_id = ?
					';
		$session_data =  $this->db->query($sql, array($session_id))->fetchSingle();
		
		return $session_data;
	}
	
	/**
	 *  Custom write() function
	 *
	 *  @access private
	 */
	function write($session_id, $session_data)
	{
		global $registry;
		
		// first checks if there is a session with this id
		$sql = 'SELECT COUNT(*) 
				FROM ' . TABLE_PREFIX . 'sess
				WHERE s_id = ?
				LIMIT 1';
		$count = $this->db->query($sql, array($session_id))->fetchSingle();
		
		// if there is
		if($count > 0)
		{
			// update the existing session's data
			// and set new expiry time
			$sql = 'UPDATE ' . TABLE_PREFIX . 'sess
					SET s_data = ?,
						s_userid = ?,
						s_dateexpired = ?
					WHERE s_id = ?
					LIMIT 1';
			if($this->db->query($sql, array($session_data, (int)$registry->me->id, time() + $this->sessionLifetime, $session_id))->rowCount() > 0)
			{
				return true;
			}
		}
		else 
		{
		
			
			$sql = 'INSERT INTO ' . TABLE_PREFIX . 'sess(`s_id`, `s_data`, `s_agent`, `s_ipaddress`, `s_hash`, `s_userid`, `s_dateexpired`, `s_datecreated`)
					VALUES(?, ?, ?, ?, ?, ?, ?, ?)';
			if($this->db->query($sql, array($session_id, $session_data, $_SERVER['HTTP_USER_AGENT'], Helper::getIpAddress(true), md5($_SERVER['HTTP_USER_AGENT'] . '-' . Helper::getIpAddress()), (int)$registry->me->id, time() + $this->sessionLifetime, time()))->rowCount() > 0)
				return '';
		}
		
		// if something went wrong, return false
		return false;
		
	}
	
	/**
	 *  Custom destroy() function
	 *
	 *  @access private
	 */
	function destroy($session_id)
	{
		
		// deletes the current session id from the database
		$sql = 'DELETE FROM ' . TABLE_PREFIX . 'sess
				WHERE s_id = ?
				';
		if($this->db->query($sql, array($session_id))->rowCount() > 0)
			return true;
			
		// if something went wrong, return false
		return false;
		
	}
	
	/**
	 *  Custom gc() function (garbage collector)
	 *
	 *  @access private
	 */
	function gc($maxlifetime)
	{
		// it deletes expired sessions from database
		$sql = 'DELETE FROM ' . TABLE_PREFIX . 'sess
				WHERE s_dateexpired < ?   ';
		$this->db->query($sql, array(time()));
		
		
	}
	
}
?>
