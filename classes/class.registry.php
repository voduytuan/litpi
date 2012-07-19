<?php

Class Registry Implements ArrayAccess 
{
	private $vars = array();
	public static $base_dir = '';
	
	function __construct() 
	{
	}

	function set($key, $var) 
	{
		$this->vars[$key] = $var;
		return true;
	}

	function get($key) 
	{
		if (isset($this->vars[$key]) == false) 
		{
			return null;
		}

		return $this->vars[$key];
	}
	
	function __set($key, $var)
	{
		$this->vars[$key] = $var;
		return true;
	}
	
	function __get($key)
	{
		if (isset($this->vars[$key])) 
		{
			return $this->vars[$key];
		}

		return null;
	}

	function remove($var) 
	{
		unset($this->vars[$key]);
	}

	function offsetExists($offset) 
	{
		return isset($this->vars[$offset]);
	}

	function offsetGet($offset) 
	{
		return $this->get($offset);
	}

	function offsetSet($offset, $value) 
	{
		$this->set($offset, $value);
	}

	function offsetUnset($offset) 
	{
		unset($this->vars[$offset]);
	}
	
	/**
    * Ham xu ly tinh toan de tra ve ROOT URL cua Resource 
    * 
    * De lam giam tai cho main site
    * Co the xu ly cai tien cho phep su dung CDN voi nhieu domain
    * Hien tai chi co 1 domain la r-pto.com duoc cau hinh trong file include/config.php ma thoi
    * nen return ve gia tri nay luon
    * 
    * @param string $type: loai resource de co the cau hinh duong dan resource cho tot hon dua vao loai resource
    * - 1 so loai resource la: static (css, img, js cua template), photo va user avatar (big,small,medium)
    * 
    */
    public function getResourceHost($type = '')
    {
    	global $setting;
    	
    	$path = '';
    	
		$path = $setting['resourcehost']['general'];
    	
    	return $path;
	}
}

