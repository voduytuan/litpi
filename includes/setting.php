<?php

	/**
	* Cac setting cho website
	*/

    //format
	//$setting['group']['entry'] = value;
	
	$setting['site']['heading'] = 'Litpi Framework';
	$setting['site']['smartyCompileCheck'] = true;	//true if development phase, false when go to live production
	$setting['site']['jsversion'] = '1';	//true if using cache, false if not
	$setting['site']['cssversion'] = '1';	//true if using cache, false if not
	$setting['site']['profilertrigger'] = 'xprofiler';	//GET/POST/COOKIE element to trigger the profiling data
	
	$setting['resourcehost']['general'] = $conf['rooturl'] . 'templates/default/';
	

	
		
?>
