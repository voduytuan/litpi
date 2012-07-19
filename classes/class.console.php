<?php

/* - - - - - - - - - - - - - - - - - - - - -

 Title : PHP Quick Profiler Console Class
 Author : Created by Ryan Campbell
 URL : http://particletree.com/features/php-quick-profiler/

 Last Updated : April 22, 2009

 Description : This class serves as a wrapper around a global
 php variable, debugger_logs, that we have created.

	* Moved to litpi model folder by voduytuan - 16-7-2012

- - - - - - - - - - - - - - - - - - - - - */

class Console {
	
	/*-----------------------------------
	     LOG A VARIABLE TO CONSOLE
	------------------------------------*/
	
	public static function log($data) {
		$logItem = array(
			"data" => $data,
			"callstack" => self::getParentCallStack(),
			"type" => 'log'
		);
		
		//echo $logItem['file'];
		$GLOBALS['debugger_logs']['console'][] = $logItem;
		$GLOBALS['debugger_logs']['logCount'] += 1;
	}
	
	public static function getParentCallStack()
	{
		//get backtrace
		$backtraces = debug_backtrace();
		$caller = $backtraces[2];	//2 is the array store the outside call this Console::log
		
		return $caller['file'] . ' (Line ' . $caller['line'] . ')';
	}
	
	/*---------------------------------------------------
	     LOG MEMORY USAGE OF VARIABLE OR ENTIRE SCRIPT
	-----------------------------------------------------*/
	
	public static function logMemory($object = false, $name = 'PHP') {
		$memory = memory_get_usage();
		if($object) $memory = strlen(serialize($object));
		$logItem = array(
			"data" => $memory,
			"callstack" => self::getParentCallStack(),
			"type" => 'memory',
			"name" => $name,
			"dataType" => gettype($object)
		);
		$GLOBALS['debugger_logs']['console'][] = $logItem;
		$GLOBALS['debugger_logs']['memoryCount'] += 1;
	}
	
	/*-----------------------------------
	     LOG A PHP EXCEPTION OBJECT
	------------------------------------*/
	
	public static function logError($exception, $message) {
		$logItem = array(
			"data" => $message,
			"callstack" => self::getParentCallStack(),
			"type" => 'error',
			"file" => $exception->getFile(),
			"line" => $exception->getLine()
		);
		$GLOBALS['debugger_logs']['console'][] = $logItem;
		$GLOBALS['debugger_logs']['errorCount'] += 1;
	}
	
	/*------------------------------------
	     POINT IN TIME SPEED SNAPSHOT
	-------------------------------------*/
	
	public static function logSpeed($name = 'Point in Time') {
		$logItem = array(
			"data" => PhpQuickProfiler::getMicroTime(),
			"callstack" => self::getParentCallStack(),
			"type" => 'speed',
			"name" => $name
		);
		$GLOBALS['debugger_logs']['console'][] = $logItem;
		$GLOBALS['debugger_logs']['speedCount'] += 1;
	}
	
	/*-----------------------------------
	     SET DEFAULTS & RETURN LOGS
	------------------------------------*/
	
	public static function getLogs() {
		if(!$GLOBALS['debugger_logs']['memoryCount']) $GLOBALS['debugger_logs']['memoryCount'] = 0;
		if(!$GLOBALS['debugger_logs']['logCount']) $GLOBALS['debugger_logs']['logCount'] = 0;
		if(!$GLOBALS['debugger_logs']['speedCount']) $GLOBALS['debugger_logs']['speedCount'] = 0;
		if(!$GLOBALS['debugger_logs']['errorCount']) $GLOBALS['debugger_logs']['errorCount'] = 0;
		return $GLOBALS['debugger_logs'];
	}
}

?>