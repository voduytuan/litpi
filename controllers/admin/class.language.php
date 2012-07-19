<?php

Class Controller_Admin_Language Extends Controller_Admin_Base  
{
	private $langFolder = 'language/';

	function indexAction()
	{
		$success 	= array();
		$error 	= array();
		$progress 	= array();
		$formData 	= array();
		$langPacks 	= array();
		$diffPack 	= array();  
		
		$workingLanguageDir = $this->langFolder;
		
		
		if ($dh = opendir($workingLanguageDir)) 
		{
			while (($langdir = readdir($dh)) !== false) 
			{
				if($langdir != '.' && $langdir != '..' && is_dir($workingLanguageDir.$langdir))
				{
					//truy van de lay xml file
					if($dh2 = opendir($workingLanguageDir.$langdir))
					{
						$lang = array('folder' => $langdir, 'files' => array(), 'controllergroup' => array());
						while(($file = readdir($dh2)) !== false)
						{
							if($file != '..' && $file != '.')
							{
								
								//thu muc language cua controller group
								if(is_dir($workingLanguageDir.$langdir.DIRECTORY_SEPARATOR.$file))
								{
									//tien hanh read tat ca file language xml
									$controllergrouplang = array();
									if($dh3 = opendir($workingLanguageDir.$langdir.DIRECTORY_SEPARATOR.$file))
									{
										while(($controllerfile = readdir($dh3)) !== false)
										{
											if(strtolower(substr($controllerfile,-3)) == 'xml')
											{
												$controllergrouplang[] = $controllerfile;	
											}
										}
										//sort lang files as alphabetical
										sort($controllergrouplang);
										closedir($dh3);
									} 
									
									
									$lang['controllergroup'][$file] = $controllergrouplang;
								}
								
								elseif(strtolower(substr($file,-3)) == 'xml')
								{
									$lang['files'][] = $file;	
								}
							}
							
						}
						//sort lang files as alphabetical
						ksort($lang['controllergroup']);
						sort($lang['files']);
						
						//assign new lang to package
						$langPacks[] = $lang;
						closedir($dh2);
					}
				}
				
			}
			closedir($dh);
		}
		
		$this->registry->smarty->assign(array('langPacks' 		=> $langPacks,
												'formData'			=> $formData,
												));                             
		$contents = $this->registry->smarty->fetch($this->registry->smartyControllerContainer.'view.tpl');
		
		$this->registry->smarty->assign(array(	'pageTitle'	=> $this->registry->lang['controller']['pageTitle_list'],
												'contents' 	=> $contents));
		$this->registry->smarty->display($this->registry->smartyControllerGroupContainer . 'index.tpl');		
	}
	
	
	function editAction()
	{
		$formData 	= '';
		$error 	= array();
		$success 	= array();
		$warning = array();
		$fileData 	= array();  
		
		$folder 	= $this->registry->router->getArg('folder');
		$subfolder 	= $this->registry->router->getArg('subfolder');
		$file 		= $this->registry->router->getArg('file');
		
		//read xml file
		if(strtolower(substr($file,-3)) == 'xml')
		{
			if(strlen($subfolder) > 0)
				$filepath = $this->langFolder . $folder . DIRECTORY_SEPARATOR . $subfolder . DIRECTORY_SEPARATOR . $file;
			else
				$filepath = $this->langFolder . $folder . DIRECTORY_SEPARATOR . $file;         
			
			//check write permission
			if(!is_writable($filepath))
			{
				$warning[] = $this->registry->lang['controller']['warnFileNotWritable'];
			}
			elseif(isset($_POST['fsubmit']))
			{
				$formData = $_POST;
				
				$new_xml_strings = array();       
				$attrs = $this->GetItemAttr($filepath, 0);
				
				if(isset($formData['fsortbyalphabet']))
					ksort($formData['fname']);
				
				foreach($formData['fname'] as $name=>$value)
				{
					$value = Helper::mystripslashes($value);
					$new_xml_strings[] = new XmlNode( "lines", $attrs[$name], null, $value ); 
				}
				
				
				$xml_strings = new XmlNode( "data", array(), $new_xml_strings );
				$obj_saver = new Object2Xml(true);
				$obj_saver->Save( $xml_strings, $filepath);
				unset( $xml_strings, $new_xml_strings, $obj_saver);
				
				$success[] = $this->registry->lang['controller']['succSave'];
				$this->registry->me->writelog('languageedit', 0, array('filename' => $filepath));
			}
			
			
			$xml = new SimpleXMLElement($filepath, null, true);
			foreach($xml->lines as $line)
			{
				$fileData[] = array('name' => (string)($line->attributes()->name),
									'descr' => (string)($line->attributes()->descr),
									'values' => (string)$line);
			}	
			
		}
		else
		{
			$error[] = $this->registry->lang['controller']['errNotFound'];
		}
		
		$this->registry->smarty->assign(array('fileData'		=> $fileData,
												'folder'		=> $folder,
												'subfolder'		=> $subfolder,
												'success'		=> $success,
												'error'			=> $error,
												'warning'		=> $warning,
												'langFolder'	=> $this->langFolder,
												'file'			=> $file,
												));
		$contents = $this->registry->smarty->fetch($this->registry->smartyControllerContainer.'edit.tpl');
		
		$this->registry->smarty->assign(array(	'pageTitle'	=> $this->registry->lang['controller']['pageTitle_edit'],
												'contents' 	=> $contents));
		$this->registry->smarty->display($this->registry->smartyControllerGroupContainer . 'index.tpl');		
	}
	
	
	#########################################################
	############	PRIVATE FUNCTION
	########################################################
	private function GetItemAttr($file_name) 
	{
		$xml_parser = new SimpleXmlParser($file_name);
		$xml_root = $xml_parser->getRoot();

		$_array = array();

		foreach ( $xml_root->children as $node) 
		{
			switch($node->tag) 
			{
				case "lines":   $_array[$node->attrs["name"]] = $node->attrs; break;
			}
		}

		return $_array;
	}
	
	/**
	* Find the all language pack options
	* 
	* @param boolean $isFrontend
	*/
	private function getLanguageOptions()
	{
		$output = array();
		
		
		
		$dir = $this->langFolder;
		
		if ($dh = opendir($dir)) 
		{
			while (($langdir = readdir($dh)) !== false) 
			{
				if($langdir != '.' && $langdir != '..' && is_dir($dir.$langdir))
				{
					$output[$langdir] = $langdir;
				}
			}
			closedir($dh);
		}
		
		if(count($output) == 2)
			sort($output);
		else
			ksort($output);
		
		return $output;
	}
	
		
	/**
	* Ham dung de return tat ca cac file language co trong 1 folder
	* 
	* @param mixed $dir
	*/
	private function returnLanguageFileArray($lang, $isFrontend = true)
	{
		if($isFrontend)
		{
			$dir = $this->langFolderFrontEnd . $lang;
		}
		else
		{
			$dir = $this->langFolderBackEnd . $lang;
		}
		
		$files = array();
		//truy van de lay xml file
		if($dh = opendir($dir))
		{
			while(($file = readdir($dh)) !== false)
			{
				if(strtolower(substr($file,-3)) == 'xml')
				{
					$files[] = $file;	
				}
			}
			//sort lang files as alphabetical
			sort($files);
		}
		
		return $files;
	}

}

?>