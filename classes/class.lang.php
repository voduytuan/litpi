<?php

class lang
{
	private $lang = array();
	private $defaultLang = 'vn';
	private $langPath;
	public  $langCode;
	
	function __construct($module = '')
	{
		//Find language to load
		$language = '';
		if(isset($_SESSION['language']))
		{
			$language = $_SESSION['language'];
		}
		elseif(isset($_COOKIE['language']))
		{
			$language = $_COOKIE['language'];
		}
		else 
		{
			$language = $this->defaultLang;
		}
			
		//Load lang folder
		$this->langPath = 'language/' . $language . '/';
		
		//check language folder
		//if not existed, select default folder (en)
		if(!is_dir($this->langPath))
		{
			$this->langPath = 'language/' . $this->defaultLang . '/';
		}	
		
		if($module == '')
		{
			$module = 'index';
		}
			
		$this->lang = Helper::GetLangContent($this->langPath, $module);

			
		$this->langCode = $language;
	}
	
	
	/**
	 * Set 1 cap key,value cho bien lang
	 *
	 * @param unknown_type $key
	 * @param unknown_type $value
	 * @param unknown_type $overwrite
	 */
	public function setLang($key, $value, $overwrite = false)
	{
		if($overwrite == true)
		{
			$this->lang[$key] = $value;
		}
		else 
		{
			if(array_key_exists($key, $this->lang))
			{
				die('set Lang failed.<br>key: <b>' . $key . '</b> existed.');
			}
			else 
			{
				$this->lang[$key] = $value;
			}
		}
	}
	
	/**
	 * Dich 1 chuoi ra dung language
	 *
	 * @param unknown_type $string
	 * @return unknown
	 */
	public function translate($string)
	{
		if(array_key_exists($string,$this->lang))
		{
			return $this->lang[$string];
		}
		else 
		{
			return $string;
		}
	}
	
}

//abstract class for lang inheritant
abstract class lang_abstract
{
	public $lang = array();
	
	public function __construct()
	{
		$this->setDefaultLang();
	}
	
	/**
	 * Ham de them mot lang vao danh sach
	 *
	 * @param mixed $lang
	 */
	public function insertLang($lang)
	{
		if(is_array($lang))
		{
			$this->lang = array_merge($this->lang, $lang);
		}
		else 
		{
			$this->lang[] = $lang;
		}
	}
	
	/**
	 * Ham tra ve ket qua cua entry can tim
	 *
	 * @param unknown_type $lang
	 * @return unknown
	 */
	public function getLang($lang)
	{
		if(isset($this->lang[$lang]))
		{
			return $this->lang['lang'];
		}
		else 
		{
			return '';
		}
	}
	
	private function setDefaultLang()
	{
		$lang = array();
		
		$this->lang = $lang;
	}
}

?>