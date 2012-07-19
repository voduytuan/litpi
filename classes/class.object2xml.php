<?php
/**
 * Class save object (XmlNode) to xml-file
 *
 * @package JobSitePro
 * @subpackage Include
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author $Author: ekluzheva $
 * @version $Revision: 1.4 $ $Date: 2007/07/31 05:37:06 $
 */

class Object2Xml 
{
	/**
	 * Tab count
	 *
	 * @access private
	 * @var string
	 */
	var $__tabs = "";

	/**
	 * Object root element
	 *
	 * @access private
	 * @var object
	 */
	var $__xml;

	/**
	 * If true, then save all data as CDATA
	 *
	 * @access public
	 * @var boolean
	 */
	var $__in_cdata = false;

	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 */
	function Object2Xml( $all_in_cdata = false ) 
	{
		$this->__in_cdata = $all_in_cdata;
	}

	/**
	 * Save object as xml-file
	 *
	 * @access public
	 * @param object $xml_root Root XmlNode
	 * @param string $file_path Path - destination file
	 * @return boolean
	 */
	function Save($xml_root, $file_path) 
	{
		$this->__xml = fopen($file_path, "w");
		fwrite($this->__xml, "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\r\n");
		$this->__SaveChild($xml_root, 0);
		fclose($this->__xml);
	}

	/**
	 * Recourse method, save xmlnode
	 *
	 * @access private
	 * @param object $child
	 * @param integer $tabs_count Tab count
	 * @return void
	 */
	function __SaveChild($child, $tabs_count) 
	{
		// insert $tab_count :)
		$str = "";

		for ($i = 0; $i < $tabs_count; ++$i) 
		{
			$str .= "\t";
		}

		$str .= "<".$child->tag;

		if ( isset($child->attrs) && count($child->attrs) ) 
		{
			foreach ( $child->attrs as $name => $value ) 
			{
				$str .= " ".$name."=\"".htmlspecialchars($value)."\"";
			}
		}

		$str .= ( $this->__in_cdata && !empty( $child->value ) ) ? "><![CDATA[" : ">";
		$str .= $child->value;

		if ( $child->childrenCount ) 
		{
			$str .= "\n";
		}

		fwrite($this->__xml, $str);

		if ( $child->childrenCount ) 
		{
			foreach ( $child->children as $cnt => $value ) 
			{
				$this->__SaveChild($value, $tabs_count+1);
			}
		}

		$str = "";

		if ( $child->childrenCount ) 
		{
			// insert $tab_count :)
			for ($i = 0; $i < $tabs_count; ++$i) 
			{
				$str .= "\t";
			}
		}

		$str .= ( $this->__in_cdata && !empty( $child->value ) ) ? "]]></" : "</";
		$str .= $child->tag.">\n";
		fwrite( $this->__xml, $str );
	}
}

?>