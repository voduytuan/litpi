<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty format price replace modifier plugin
 *
 * Type:     modifier<br>
 * Name:     formatprice<br>
 * Purpose:  convert string vietnamese price format
 * @author   lonelyworlf(tuanmaster2002@yahoo.com)
 * @param string
 * @return string
 */
function smarty_modifier_formatpricereplace($string)
{
	
	return  preg_replace( 
    "~-?(\.\d+|\d+\.?\d*)~e", 
    "number_format($0, 0, '.', ',')", 
    $string);
    
	
}

?>
