<?php
Abstract Class Core_Object
{
	protected $db;
	
	public function __construct()
	{
		global $registry;
		
		$this->db = $registry->db;
	}
	
	public function __sleep()
    {
       	$this->db = null;
       	return $this;
    }
    
    
}


?>