<?php

Class Controller_Site_NotFound Extends Controller_Site_Base 
{
	
	function indexAction() 
	{
		$referer = base64_decode($_GET['r']);
		
		
		//calculate recommend url
		//strip query string
		$recommendurl = $referer;
		$querystringPos = strrpos($recommendurl, '?');
		if($querystringPos !== false)
			$recommendurl = substr($recommendurl, 0, $querystringPos);
		//strip page
		$pagePos = strrpos($recommendurl, '/page-');
		if($pagePos !== false)
			$recommendurl = substr($recommendurl, 0, $pagePos);
			
		if($recommendurl == $referer)
			$recommendurl = '';
		
		$this->registry->smarty->assign(array('referer' => $referer, 'recommendurl' => $recommendurl));
				
		
		$contents = $this->registry->smarty->fetch($this->registry->smartyControllerContainer . 'index.tpl');
		$this->registry->smarty->assign('contents', $contents);
		$this->registry->smarty->display($this->registry->smartyControllerGroupContainer.'index.tpl');     
		
		
		
	} 
}

?>