<?php

class Helper
{
	/**
	* Unique ID generation.
	*/
	public static function unique_id()
	{
		return md5(uniqid(rand(), true));
	}


	
	/**
	 * Convert normal url text to hyperlink
	 *
	 * @param string $input
	 * @return string
	 */
	public static function AutoLink($input)
	{
		$output = preg_replace("/(http|https|ftp)://([a-z0-9\-\./]+))/", "<a href=\"\\0\">\\0</a>", $input);
		$output = preg_replace("/(([a-z0-9\-\.]+)@([a-z0-9\-\.]+)\.([a-z0-9]+))/", "<a href=\"mailto:\\0\">\\0</a>", $output);
		return $output;
	}
	
	public static function refineUrl($input)
	{
		$input = strtolower(trim(strip_tags($input)));
		
		if($input != '' && preg_match('/(http|ftp|https):\/\/.*/', $input) == 0)
			$input = 'http://' . $input;
			
		return $input;
	}



	/**
	 * Generate a random number between floor and ceiling
	 *
	 * @param int $floor
	 * @param int $ceiling
	 * @return int
	 */
	static function RandomNumber($floor, $ceiling) 
	{
		srand((double)microtime() * 1000000);
		return rand($floor, $ceiling);
	}

	/**
	 * Format string of filesize
	 *
	 * @param string $s
	 * @return string
	 */
	static function formatFileSize($s) 
	{
		if($s >= "1073741824")
		{ 
			$s = number_format($s / 1073741824, 2) . " GB"; 
		}
		elseif($s >= "1048576") 
		{ 
			$s  = number_format($s / 1048576, 2) . " MB"; 
		}
		elseif($s >= "1024") 
		{ 
			$s = number_format($s / 1024, 2) . " KB"; 
		}
		elseif($s >= "1") 
		{  
			$s = $s . " bytes"; 
		}
		else 
		{ 
			$s = "-"; 
		}
		
		return $s;

	}

	/**
	 * return file extension
	 *
	 * @param	string	$filename
	 * @return	string
	 */
	public static function fileExtension($filename)
	{
		return strtolower(trim(substr(strrchr($filename, '.'), 1)));
	}

	public static function ValidatedEmail($email) 
	{
	     return preg_match('/^[\w.-]+@([\w.-]+\.)+[a-z]{2,6}$/is', $email);
	}




	public static function stripslashes_deep($value)
	{
	   $value = is_array($value) ?
				   array_map('stripslashes_deep', $value) :
				   stripslashes($value);

	   return $value;
	}



		
	/**
	* Fix back button on IE6 (stupid) browser
	* @author khanhdn
	*/
	public static function fixBackButtonOnIE() 
	{
		//drupal_set_header("Expires: Sat, 27 Oct 1984 08:52:00 GMT GMT");	// Always expired (1)
		//drupal_set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");	// always modified (2)
		header("Cache-Control: no-store, no-cache, must-revalidate");	// HTTP/1.1 (3)
		header("Cache-Control: public");	//(4)
		header("Pragma: no-cache");	// HTTP/1.0   (5)
		ini_set('session.cache_limiter', 'private');   // (6)
	}

	public static function getSessionId()
	{
		$s_id = session_id();
		$_SESSION['s_id'] = $s_id;
		return $s_id;
		
	}


	/**
	* Ham dung de convert cac ky tu co dau thanh khong dau
	* Dung tot cho cac chuc nang SEO cho browser(vi nhieu engine ko 
	* hieu duoc dau tieng viet, nen can phai bo dau tieng viet di)
	* 
	* @param mixed $string
	*/
	public static function codau2khongdau($string = '', $alphabetOnly = false, $tolower = true)
	{
		
		$output =  $string;
		if($output != '')
		{
			//Tien hanh xu ly bo dau o day
			$search = array('&#225;', '&#224;', '&#7843;', '&#227;', '&#7841;', 				// a' a` a? a~ a.
							'&#259;', '&#7855;', '&#7857;', '&#7859;', '&#7861;', '&#7863;',	// a( a('
							'&#226;', '&#7845;', '&#7847;', '&#7849;', '&#7851;', '&#7853;', 	// a^ a^'..
							'&#273;',											   			// d-
							'&#233;', '&#232;', '&#7867;', '&#7869;', '&#7865;',				// e' e`..
							'&#234;', '&#7871;', '&#7873;', '&#7875;', '&#7877;', '&#7879;',	// e^ e^'
							'&#237;', '&#236;', '&#7881;', '&#297;', '&#7883;',					// i' i`..
							'&#243;', '&#242;', '&#7887;', '&#245;', '&#7885;',					// o' o`..
							'&#244;', '&#7889;', '&#7891;', '&#7893;', '&#7895;', '&#7897;',	// o^ o^'..
							'&#417;', '&#7899;', '&#7901;', '&#7903;', '&#7905;', '&#7907;',	// o* o*'..
							'&#250;', '&#249;', '&#7911;', '&#361;', '&#7909;',					// u'..
							'&#432;', '&#7913;', '&#7915;', '&#7917;', '&#7919;', '&#7921;',	// u* u*'..
							'&#253;', '&#7923;', '&#7927;', '&#7929;', '&#7925;',				// y' y`..
							
							'&#193;', '&#192;', '&#7842;', '&#195;', '&#7840;',					// A' A` A? A~ A.
							'&#258;', '&#7854;', '&#7856;', '&#7858;', '&#7860;', '&#7862;',	// A( A('..
							'&#194;', '&#7844;', '&#7846;', '&#7848;', '&#7850;', '&#7852;',	// A^ A^'..
							'&#272;',															// D-
							'&#201;', '&#200;', '&#7866;', '&#7868;', '&#7864;',				// E' E`..
							'&#202;', '&#7870;', '&#7872;', '&#7874;', '&#7876;', '&#7878;',	// E^ E^'..
							'&#205;', '&#204;', '&#7880;', '&#296;', '&#7882;',					// I' I`..
							'&#211;', '&#210;', '&#7886;', '&#213;', '&#7884;',					// O' O`..
							'&#212;', '&#7888;', '&#7890;', '&#7892;', '&#7894;', '&#7896;',	// O^ O^'..
							'&#416;', '&#7898;', '&#7900;', '&#7902;', '&#7904;', '&#7906;',	// O* O*'..
							'&#218;', '&#217;', '&#7910;', '&#360;', '&#7908;',					// U' U`..
							'&#431;', '&#7912;', '&#7914;', '&#7916;', '&#7918;', '&#7920;',	// U* U*'..
							'&#221;', '&#7922;', '&#7926;', '&#7928;', '&#7924;'				// Y' Y`..
							);
							
			$search2 = array('á', 'à', 'ả', 'ã', 'ạ', 				// a' a` a? a~ a.
							'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ',	// a( a('
							'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 	// a^ a^'..
							'đ',											   			// d-
							'é', 'è', 'ẻ', 'ẽ', 'ẹ',				// e' e`..
							'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ',	// e^ e^'
							'í', 'ì', 'ỉ', 'ĩ', 'ị',					// i' i`..
							'ó', 'ò', 'ỏ', 'õ', 'ọ',					// o' o`..
							'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ',	// o^ o^'..
							'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ',	// o* o*'..
							'ú', 'ù', 'ủ', 'ũ', 'ụ',					// u'..
							'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự',	// u* u*'..
							'ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ',				// y' y`..
							
							'Á', 'À', 'Ả', 'Ã', 'Ạ',					// A' A` A? A~ A.
							'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ',	// A( A('..
							'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ',	// A^ A^'..
							'Đ',															// D-
							'É', 'È', 'Ẻ', 'Ẽ', 'Ẹ',				// E' E`..
							'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ',	// E^ E^'..
							'Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị',					// I' I`..
							'Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ',					// O' O`..
							'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ',	// O^ O^'..
							'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ',	// O* O*'..
							'Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ',					// U' U`..
							'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự',	// U* U*'..
							'Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ'				// Y' Y`..
							);
							
			$replace = array('a', 'a', 'a', 'a', 'a',
							 'a', 'a', 'a', 'a', 'a', 'a',
							 'a', 'a', 'a', 'a', 'a', 'a',
							 'd',
							 'e', 'e', 'e', 'e', 'e',
							 'e', 'e', 'e', 'e', 'e', 'e',
							 'i', 'i', 'i', 'i', 'i',
							 'o', 'o', 'o', 'o', 'o',
							 'o', 'o', 'o', 'o', 'o', 'o',
							 'o', 'o', 'o', 'o', 'o', 'o',
							 'u', 'u', 'u', 'u', 'u',
							 'u', 'u', 'u', 'u', 'u', 'u',
							 'y', 'y', 'y', 'y', 'y',
							 
							 'A', 'A', 'A', 'A', 'A',
							 'A', 'A', 'A', 'A', 'A', 'A',
							 'A', 'A', 'A', 'A', 'A', 'A',
							 'D',
							 'E', 'E', 'E', 'E', 'E', 
							 'E', 'E', 'E', 'E', 'E', 'E',
							 'I', 'I', 'I', 'I', 'I',
							 'O', 'O', 'O', 'O', 'O', 
							 'O', 'O', 'O', 'O', 'O', 'O',
							 'O', 'O', 'O', 'O', 'O', 'O',
							 'U', 'U', 'U', 'U', 'U', 
							 'U', 'U', 'U', 'U', 'U', 'U',
							 'Y', 'Y', 'Y', 'Y', 'Y'
							);
			//print_r($search);
			$output = str_replace($search, $replace, $output);
			$output = str_replace($search2, $replace, $output);
			
			if($alphabetOnly)
			{
				$output = self::alphabetonly($output);
			}
			
			if($tolower)
			{
				$output = strtolower($output);
			}
		}
		
		return $output;
	}

	public static function alphabetonly($string = '')
	{
		$output = $string;
		//replace no alphabet character
		$output = preg_replace("/[^a-zA-Z0-9]/","-", $output);   
		$output = preg_replace("/-+/","-", $output);   
		$output = trim($output, '-');
		
		return $output;
	}

	public static function getIpAddress($convertToInteger = false) 
	{

		$ip = '';

		if($_SERVER) 
		{
			if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			{
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			elseif(isset($_SERVER['HTTP_CLIENT_IP']))
			{
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
			else
			{
				$ip = $_SERVER['REMOTE_ADDR'];
			}
		} 
		else 
		{
			if(getenv('HTTP_X_FORWARDED_FOR'))
			{
				$ip = getenv('HTTP_X_FORWARDED_FOR');
			}
			elseif(getenv('HTTP_CLIENT_IP'))
			{
				$ip = getenv('HTTP_CLIENT_IP');
			}
			else
			{
				$ip = getenv('remote_addr');
			}
		}

		//Convert IP string to Integer
		//Example, IP: 127.0.0.1 --> 2130706433
		if($convertToInteger)
			$ip = ip2long($ip);
			
		return $ip;
	}

		
	/**
	* Ham dung de strip slash tu 1 chuoi
	*  - neu chuoi do' duoc submit va da duoc them slash(do config: magic_quotes_gpc) thi tien hanh strip slash
	* - nguoc lai, return chuoi
	* 
	* @param string $string
	* @return string
	*/
	public static function mystripslashes($string)
	{
		if(get_magic_quotes_gpc())
		{
			return stripslashes($string);
		}
		else
		{
			return $string;
		}
	}




	public static function GetLangContent($langPath = '', $module_name)
	{
		$lang_content = array();
		$langFile = $langPath . $module_name . '.xml';
		if (file_exists($langFile)) 
		{
			$xml = new SimpleXMLElement($langFile, null, true);
			foreach($xml->lines as $line)
			{
				$lang_content["{$line->attributes()->name}"] = (string)$line;
			}	
		}
		
		return $lang_content;
	}




	/**
	* Function de kiem tra input cua 1 query trong truong hop input la 1 string
	* 
	* Thong qua bo loc nay de han che sql string injection va blind sql injection
	* 
	* @param mixed $str
	*/
	public static function queryFilterString($str)
	{
		//Use RegEx for complex pattern
		$filterPattern = array(
								'/select.*(from|if|into)/i',  // select table query, 
								'/0x[0-9a-f]*/i',				// hexa character
								'/\(.*\)/',						// call a sql function
								'/union.*select/i',				// UNION query
								'/insert.*values/i',		// INSERT query
								'/order.*by/i'				// ORDER BY injection
								);
		$str = preg_replace($filterPattern, '', $str);
		
		//Use normal replace for simple replacement
		$filterHaystack = array(
								'--',	// query comment
								'||',	// OR operator
								'\*',	// OR operator
								);
		
		$str = str_replace($filterHaystack, '', $str);
		return $str;
	}

	// added: 08/10/2008
	// Function dung de gzip content of output buffer
	// cach su dung:
	//		1. goi 2 ham: // At the beginning of each page call these two functions
	//						ob_start();
	//						ob_implicit_flush(0);
	//		2. echo noi dung
	//		3. call ham nay: print_gzipped_page()
	static function print_gzipped_page()
	{
		
		if( headers_sent() )
		{
		    $encoding = false;
		}
		elseif( strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false )
		{
		    $encoding = 'x-gzip';
		}
		elseif( strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false )
		{
		    $encoding = 'gzip';
		}
		else
		{
		    $encoding = false;
		}
		

		if( $encoding )
		{
		    $contents = ob_get_contents();
			$size = strlen($contents);
			$Crc = crc32($contents);
			$contents = gzcompress($contents, 7);
			$contents = substr($contents, 0, strlen($contents) - 4);

			ob_end_clean();
			header('Content-Encoding: ' . $encoding);
			print("\x1f\x8b\x08\x00\x00\x00\x00\x00");

			print($contents);

			echo pack('V', $Crc);
			echo pack('V', $size);

			exit();
		}
		else
		{
		    ob_end_flush();
		    exit();
		}
	}


	static function displayDebugInformation($conf, $executedTime, $queryArray)
	{
	    $memoryUsage = round(memory_get_peak_usage(TRUE)/1024, 2);
	    
		echo '
			<table width="100%" cellpadding="5" cellspacing="1" style="background-color:#fff;border-color:#eee;font-family:Arial, Helvetica, sans-serif;font-size:12px;">
				<tr style="font-weight:bold;background-color:#ccc;">
					<td style="font-size:14px;" height="40">DEBUG INFORMATION</td>
				</tr>
				<tr style="background:#ddd;"><td height="28" style="padding-left:20px;font-weight:bold;">&raquo; General Information</td></tr>
				<tr><td style="padding:10px 5px 10px 40px;line-height:2;">
						Executed in <em><strong>' . $executedTime . '</strong></em> second(s).<br />
						GZIP is <em><strong>' . ($conf['usingGZIP'] ? 'ON' : 'OFF') . '</strong></em>.	   <br />
						Memory Usage : <em><strong>' . number_format($memoryUsage, 0, '.', ',') . '</strong></em> KBs.
					</td>
				</tr>
				<tr style="background:#ddd;"><td height="28" style="padding-left:20px;font-weight:bold;">&raquo; Database Query</td></tr>
				<tr><td style="padding:10px 5px 10px 40px;line-height:3;">
						Total ' . count($queryArray) . ' ' . (count($queryArray) > 1 ? 'queries' : 'query') . ': <br />
						<ol>
							';
		
		foreach($queryArray as $query)
		{
			//$query = preg_replace('/(SET|FROM|WHERE|INNER|LEFT|RIGHT|JOIN|AND|OR|LIMIT|GROUP\sBY|ORDER\sBY|VALUES|ON|)/','<span style="font-weight:bold;">\1</span>', $query);
			$query = preg_replace('/(FROM|WHERE|SET|INNER|LEFT|RIGHT|JOIN|AND|ORDER\sBY|GROUP\sBY|OR|AS|LIMIT|ON|HAVING)\s/', '<span style="font-weight:bold;">\1 </span>', $query);
			
			$query = preg_replace('/('.TABLE_PREFIX.'[_a-z]+)/', '<span style="text-decoration:underline;">\1</span>', $query);
			$query = str_ireplace(array('SELECT', 'INSERT', 'UPDATE', 'DELETE'),
									array(
											'<span style="font-weight:bold;color:#339933;">SELECT</span>' ,
											'<span style="font-weight:bold;color:#0066CC;">INSERT</span>' ,
											'<span style="font-weight:bold;color:#FF9900;">UPDATE</span>' ,
											'<span style="font-weight:bold;color:#FF3300;">DELETE</span>'
									
									),
									$query);
									

			
			echo '<li style="width:2000px;">' . $query . '</li>';
		}	
							
		echo '
						</ol>
					</td>
				</tr>
			</table>

		';
	}

	public static function getCurrentDateDirName($includeDay = true)
	{
		$dateArr = getdate();
		
		if($includeDay)
			$path = $dateArr['year'] . '/' . $dateArr['month'] . '/' . $dateArr['mday'] . '/'; 
		else
			$path = $dateArr['year'] . '/' . $dateArr['month'] . '/'; 
			
		
			
		return $path;
	}
	
	/**
	* Convert date string in format 'dd/mm/yyyy' and time string in format 'hh:mm'to timestamp                      
	* @param string $datestring
	* @param string $timestring  
	*/
	public static function datedmyToTimestamp($datestring = '01/01/1970', $timestring = '00:01')
	{
		$timegroup = explode(':', $timestring); 
		$dategroup = explode('/', $datestring);
		return mktime((int)trim($timegroup[0]), (int)trim($timegroup[1]), 1, (int)trim($dategroup[1]), (int)trim($dategroup[0]), (int)trim($dategroup[2]));
	}
	
	
	public static function truncate($phrase, $max_words)
	{
		$phrase_array = explode(' ',$phrase);
		if(count($phrase_array) > $max_words && $max_words > 0)
			$phrase = implode(' ',array_slice($phrase_array, 0, $max_words)).'...';
		 
		return $phrase;
	}
	
	
	public static function mb_str_ireplace($co, $naCo, $wCzym)
	{
	    $wCzymM = mb_strtolower($wCzym);
	    $coM    = mb_strtolower($co);
	    $offset = 0;
	   
	        while(!is_bool($poz = mb_strpos($wCzymM, $coM, $offset)))
		    {
		        $offset = $poz + mb_strlen($naCo);
		        $wCzym = mb_substr($wCzym, 0, $poz). $naCo .mb_substr($wCzym, $poz+mb_strlen($co));
		        $wCzymM = mb_strtolower($wCzym);
		    }
	   
	    return $wCzym;
	} 

	public static function getMediaPlayer($mediaUrl, $templateFolder = 'default', $videoWidth = 400, $videoHeight=170)
	{
		$mediaPlayerFileType 		= array('WMA', 'WMV', 'ASF');
		$jwFlvMediaPlayerFileType 	= array('FLV', 'MP3', 'MP4', 'AAC', 'JPG', 'PNG', 'GIF');
		$flashPlayerFileType 		= array('SWF');
		$mediaBox 					= '';

		$extension = strtoupper(substr($mediaUrl, strrpos($mediaUrl, '.')+1));


		if(strlen($mediaUrl) == 0)
		{
			$mediaBox = '<div style="margin: 15px 0;font-size:14px;color:#aaaaaa;font-weight:bold;">[[THERE IS NO MEDIA FILE FOR THIS FILE]]</div>';
		}
		elseif(in_array($extension, $jwFlvMediaPlayerFileType))
		{
			//adjust size for video file
			if($extension == 'FLV' || $extension == 'MP4')
			{
				$videoWidth = 400;
				$videoHeight = 320;
			}

			$mediaBox = '<div id="mediaContainer"></div>
						<script type="text/javascript" src="'.$templateFolder.'/js/swfobject.js"></script>
						<script type="text/javascript">
						    var mediaplayer = new SWFObject("'.$templateFolder.'/js/jwflvmediaplayer.swf","player","'.$videoWidth.'","'.$videoHeight.'","9");
							mediaplayer.addParam("allowfullscreen","true");
							mediaplayer.addParam("allowscriptaccess","always");
							mediaplayer.addParam("flashvars","file='.$mediaUrl.'&autostart=false");
					  		mediaplayer.write("mediaContainer");
						</script>
						';

		}
		elseif(in_array($extension, $mediaPlayerFileType))
		{
			//adjust size for video file
			if($extension == 'WMV')
			{
				$videoWidth = 400;
				$videoHeight = 320;
			}
			$mediaBox = '<OBJECT ID="MediaPlayer" WIDTH="'.$videoWidth.'" HEIGHT="'.$videoHeight.'" CLASSID="CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95" STANDBY="Loading Windows Media Player components..." TYPE="application/x-oleobject">
							<PARAM NAME="FileName" VALUE="'.$mediaUrl.'">
							<PARAM name="autostart" VALUE="false">
							<PARAM name="ShowControls" VALUE="true">
							<param name="ShowStatusBar" value="true">
							<PARAM name="ShowDisplay" VALUE="false">
							<EMBED TYPE="application/x-mplayer2" SRC="'.$mediaUrl.'" NAME="MediaPlayer" WIDTH="'.$videoWidth.'" HEIGHT="'.$videoHeight.'" ShowControls="1" ShowStatusBar="1" ShowDisplay="0" autostart="0"> </EMBED>
						</OBJECT>
						';
		}
		elseif(in_array($extension, $flashPlayerFileType))
		{
			$videoWidth = 400;
			$videoHeight = 320;
			$mediaBox = '<div id="mediaContainer"></div>
						<script type="text/javascript" src="'.$templateFolder.'/js/swfobject.js"></script>
						<script type="text/javascript">
						  var mediaplayer = new SWFObject("'.$mediaUrl.'","mpl","'.$videoWidth.'","'.$videoHeight.'","9");
						  mediaplayer.write("mediaContainer");
						</script>
						';
		}
		else
		{
			$mediaBox = 'File Type(.'.$extension.') is not supported.';
		}


		return $mediaBox;
	}
	
	public static function refineMoneyString($moneyString = '')
	{
		$money = preg_replace('/[^0-9]/i', '', $moneyString);
		
		return (float)$money;
	}
	
	public static function formatPrice($money)
	{
		if(is_numeric($money))
			return number_format($money, 0, '.', ',');
		else
			return $money;
	}
	
	/**
	* Ham kiem tra coockie co duoc enable trong trinh duyet khong
	* 
	* Neu khong enable thi se anh huong toi 1 so chuc nang lien quan toi SESSION
	* cho nen can kiem tra enable coockie thi moi pass mot so chuc nang lien quan toi counting trong session nhu increase view, add comment...
	* 
	*/
	
	public static function checkCookieEnable($cookieSample = 'SHASH')
	{
		return isset($_COOKIE[$cookieSample]);
	}
	
	
	/**
	* Manual css filter
	* 
	* @param mixed $data
	* @return mixed
	*/
	public static function xss_clean($data)
	{

		// Fix &entity\n;
		$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

		// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

		do
		{
			// Remove really unwanted tags
			$old_data = $data;
			$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		}
		while ($old_data !== $data);

		// we are done...
		return $data;
	}
	
	
	/**
	* Tao token cho cac thao tac add, edit, delete entry,quiz,comment,user...
	* de chong lai tan cong CSRF
	* 
	*/
	public static function getSecurityToken()
	{
		return md5(Helper::RandomNumber(1, 1000) . session_id());
	}
	
	/**
	* Ham dung de test general security token
	* 
	* duoc tao trong $_SESSION['securityToken'] va duoc truyen vao bang $_GET['token']
	* 
	*/
	public static function checkSecurityToken()
	{
		return $_GET['token'] == $_SESSION['securityToken'];
	}
	
	/**
	* Ham replace cac ky tu dash thua (double dash --> single dash, remove first and last dash in url)
	* 
	* @param mixed $url
	*/
	public static function refineDashInUrl($url)
	{
		$url = preg_replace('/[-]+/', '-', $url);
		if($url[0] == '-')
			$url = substr($url, 1);
		
		if($url[strlen($url)-1] == '-')
			$url = substr($url,0, strlen($url)-1);
			
		return $url;
	}
	
	/**
	* Download external file using cURL
	* 
	* @param string $img : URL of external file
	* @param string $fullpath : local filepath
	* @param string $type: type of external file.
	*/
	public static function saveExternalFile($img,$fullpath, $type='image')
	{
		
		//$fullpath = urlencode($fullpath);
		$ch = curl_init ($img);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$rawdata=curl_exec($ch);
		curl_close ($ch);
		
		//check if return error (include html in output)
		if(strpos($rawdata, 'html') === false)
		{
			$fp = fopen($fullpath,'w');
			
			if(!$fp)
			{
				return false;
			}
			else
			{
				fwrite($fp, $rawdata);
				fclose($fp);	
				return true;
			}
		}
		else
		{
			return false;
		}
		
		
	}
	
	public static function bbcode_format ($str) 
	{  
	    $simple_search = array(  
	                //added line break  
	                '/\[b\](.*?)\[\/b\]/is',  
	                '/\[i\](.*?)\[\/i\]/is',  
	                '/\[u\](.*?)\[\/u\]/is',  
	                '/\[url\=(.*?)\](.*?)\[\/url\]/is',  
	                '/\[url\](.*?)\[\/url\]/is',
	                '/\[align\=(left|center|right)\](.*?)\[\/align\]/is',  
	                '/\[img\](.*?)\[\/img\]/is',  
	                '/\[img size=(.*?)\](.*?)\[\/img\]/is',  
	                '/\[mail\=(.*?)\](.*?)\[\/mail\]/is',  
	                '/\[mail\](.*?)\[\/mail\]/is',  
	                '/\[font\=(.*?)\](.*?)\[\/font\]/is',  
	                '/\[size\=(.*?)\](.*?)\[\/size\]/is',  
	                '/\[color\=(.*?)\](.*?)\[\/color\]/is',  
	                //added paragraph  
	              '/\[p\](.*?)\[\/p\]/is',  
	              '/\[youtube\]http:\/\/www\.youtube\.com\/watch\?v=([A-Za-z0-9._%-]*)[&\w;=\+_\-]*\[\/youtube\]/is',  
	               '/\[mp3\](.*?)\[\/mp3\]/is',  
	                );  
	  
	    $simple_replace = array(  
					//added line break  
	                '<strong>$1</strong>',  
	                '<em>$1</em>',  
	                '<u>$1</u>',  
					// added nofollow to prevent spam  
	                '<a href="$1" rel="nofollow" title="$2 - $1">$2</a>',  
	                '<a href="$1" rel="nofollow" title="$1">$1</a>',  
	                '<div style="text-align: $1;">$2</div>',  
					//added alt attribute for validation  
	                '<img src="$1" alt="" />',
	                '<img src="$2" width="$1" alt="" />',
	                '<a href="mailto:$1">$2</a>',  
	                '<a href="mailto:$1">$1</a>',  
	                '<span style="font-family: $1;">$2</span>',  
	                '<span style="font-size: $1px;">$2</span>',  
	                '<span style="color: $1;">$2</span>',  
					//added paragraph  
					'<p>$1</p>',  
					'<object width="560" height="340"<param name="wmode" value="opaque"><param name="movie" value="http://www.youtube.com/v/$1&hl=en&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/$1&hl=en&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="560" height="340" wmode="opaque"></embed></object>',
					'<object height="20" width="200"><param name="wmode" value="opaque"><param value="/default/js/dewplayer.swf?mp3=$1" name="movie"><param value="always" name="allowscriptaccess"><embed height="20" width="200" allowscriptaccess="always" type="application/x-shockwave-flash" src="/default/js/dewplayer.swf?mp3=$1" wmode="opaque"></object>',  
	                );  
	  
	    // Do simple BBCode's  
	    $str = preg_replace ($simple_search, $simple_replace, $str);  
	  
	    return $str;  
	}
	
	/**
	* Ham dung de xoa cache moi khi co cap nhat thong tin gi cua entry (them,xoa,edit)
	* 
	*/
	public static function clearCache($homepageOnly = false)
	{
		global $setting;
		
		$cacheList = array();
		
		if($homepageOnly)
		{
			$cacheList[] = $setting['cache']['homepageId'];
		}
		else
		{
			$cacheList[] = $setting['cache']['homepageId'];
		}
		
		for($i = 0; $i < count($cacheList); $i++)
		{
			$myCache = new cache($cacheList[$i]);
			$myCache->clear();
			unset($myCache);
		}
	}
	
	public static function curPageURL() 
	{
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") 
		{
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} 
		else 
		{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	/**
	* Ham loc chuoi screenname
	* 
	* @param string $screenname
	*/
	public static function refineScreenname($screenname)
	{
		$screenname = preg_replace('[^a-z0-9.]', '', $screenname);
		return $screenname;
	}
	
	public static function array_strip_tags($data = array())
	{
		foreach($data as $k => $v)
		{
			if(!is_array($v))
				$data[$k] = trim(strip_tags($v));
		}
		return $data;
	}
	
	/**
	* Ham them 1 querystring vao sau url, 
	* 
	* Neu URL da co dau ? thi khong can them dau ? ma chi can them &...
	* Neu URL chua co dau ? thi them dau ?, sau do them & va query string
	* 
	* @param string $url
	* @param string $paramString
	*/
	public static function urlAddParam($url, $paramString)
	{
		//neu chua co dua ?
		if(strpos($url, '?') === false)
		{
			$url .= '?';
		}
		
		return $url . '&' . $paramString;
	}
	
	public static function truncateperiod($string, $limit = 80, $pad = '...', $break='.')
	{
		$string = strip_tags($string);
		
	    // return with no change if string is shorter than $limit 
	    if(strlen($string) <= $limit) return $string; 
	    // is $break present between $limit and the end of the string? 
	    if(false !== ($breakpoint = strpos($string, $break, $limit))) 
	    { 
    		if($breakpoint < strlen($string) - 1) 
    		{ 
    			$string = substr($string, 0, $breakpoint) . $pad; 
    		} 
	    } 
	    return $string; 
	}
	
		
	/**
	* Goi 1 async post request de khong delay cua main process
	* 
	* @param mixed $url
	*/
	public static function backgroundHttpPost($url, $paramString = '')
	{
		//$fh = fopen('backgroundpost-'.urlencode($url).'.txt', 'w');
		
		$parts=parse_url($url);

		$fp = fsockopen($parts['host'], 
			  isset($parts['port'])?$parts['port']:80, 
			  $errno, $errstr, 30);

		//fwrite($fh, 'url:'. $url . "\n");
		
		if (!$fp) 
		{
			//fwrite($fh , 'FAIL');
			return false;
		} 
		else 
		{
			$out = "POST ".$parts['path']."?".$parts['query']." HTTP/1.1\r\n";
			$out.= "Host: ".$parts['host']."\r\n";
			$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out.= "Content-Length: ".strlen($paramString)."\r\n";
			$out.= "Connection: Close\r\n\r\n";
			if ($paramString != '') $out.= $paramString;
			
			//fwrite($fh , $out);
			
			fwrite($fp, $out);
			fclose($fp);
			return true;
		}
	}
	
	
	
	/**
	* Goi 1 async get request de khong delay cua main process
	* 
	* @param mixed $url
	*/
	public static function backgroundHttpGet($url)
	{
		$parts=parse_url($url);

		$fp = fsockopen($parts['host'], 
			  isset($parts['port'])?$parts['port']:80, 
			  $errno, $errstr, 30);

		if (!$fp) 
		{
			return false;
		} 
		else 
		{
			$out = "GET ".$parts['path']."?".$parts['query']." HTTP/1.1\r\n";
			$out.= "Host: ".$parts['host']."\r\n";
			$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out.= "Connection: Close\r\n\r\n";

			fwrite($fp, $out);
			fclose($fp);
			return true;
		}
	}
	
	/**
	* Ham xu ly hinh book cover sau khi download ve server
	* Neu co dang hinh vuong va co white padding (nhu vinabook)
	* thi crop bo phan mau trang 2 ben nay de lay phan center la phan chinh cua cover
	* 
	* Level: high ^^
	* 
	* @param string $imagepath
	* @return boolean
	*/
	public static function coverCropsquare($imagepath)
	{
		$result = false;
		
		$imageinfo = getimagesize($imagepath);
		if($imageinfo[0] == $imageinfo[1] && $imageinfo[0] > 0)
		{
		    $ext = strtoupper(substr($imagepath, strrpos($imagepath, '.')+1));
		    switch($ext)
		    {
		    	case 'JPG':
		    	case 'JPEG': $im = imagecreatefromjpeg($imagepath); break;
		    	case 'GIF': $im = imagecreatefromgif($imagepath); break;
		    	case 'PNG': $im = imagecreatefrompng($imagepath); break;
			}
			
			if($im)
			{
				
				//padding background color: default white
				$bg = imagecolorallocate($im, 0xFF, 0xFF, 0xFF);
				
				// Get the image width and height.
			    $imw = imagesx($im);
			    $imh = imagesy($im);

			   
			    
			    /////////////////////////
			    // not good for vinabook image
			    /*
			    // Set the X variables.
			    $xmin = $imw;
			    $xmax = 0;
			    
			    // Start scanning for the edges.
			    for ($iy=0; $iy<$imh; $iy++)
			    {
			        $first = true;
			        for ($ix=0; $ix<$imw; $ix++)
			        {
			            $ndx = imagecolorat($im, $ix, $iy);
			            if ($ndx != $bg)
			            {
			                if ($xmin > $ix){ $xmin = $ix; }
			                if ($xmax < $ix){ $xmax = $ix; }
			                if (!isset($ymin)){ $ymin = $iy; }
			                $ymax = $iy;
			                if ($first){ $ix = $xmax; $first = false; }
			            }
			        }
			    }
			    */
			    
			    //scan for vinabook image
			    // scan by vertical line
			    // algorith: tinh mau trung binh cua 1 line, neu distanct vuot qua threshold thi tuc la edge tai X
			    for($ix = 0; $ix < $imw; $ix++)
			    {
			    	$ixTotalR = 0;
			    	$ixTotalG = 0;
			    	$ixTotalB = 0;
			    	
			    	for($iy = 0; $iy < $imh; $iy++)
			    	{
			    		$rgb = imagecolorat($im, $ix, $iy);
			    		$r = ($rgb >> 16) & 0xFF;
						$g = ($rgb >> 8) & 0xFF;
						$b = $rgb & 0xFF;
						$ixTotalR += $r;
						$ixTotalG += $g;
						$ixTotalB += $b;
					}
					
					//find average of all color in column
					$ixColumnR[$ix] = $ixTotalR/$imh;
					$ixColumnG[$ix] = $ixTotalG/$imh;
					$ixColumnB[$ix] = $ixTotalB/$imh;
					
					
				}
				
				//display to test
				/*
				echo '<table border="1" style="border-collapse:collapse"><tr>';
				for($i = 0; $i < $imw; $i++)
				{
					//find distance from previous color
					$dstprev = 0;
					if($i > 0)
					{
						$dstr = $ixColumnR[$i] - $ixColumnR[$i-1];
						$dstg = $ixColumnG[$i] - $ixColumnG[$i-1];
						$dstb = $ixColumnB[$i] - $ixColumnB[$i-1];
						$dstprev = sqrt($dstr * $dstr + $dstg * $dstg + $dstb * $dstb);
					}
					
					//find distance with next color
					$dstnext = 0;
					if($i < $imw -1)
					{
						$dstr = $ixColumnR[$i] - $ixColumnR[$i+1];
						$dstg = $ixColumnG[$i] - $ixColumnG[$i+1];
						$dstb = $ixColumnB[$i] - $ixColumnB[$i+1];
						$dstnext = sqrt($dstr * $dstr + $dstg * $dstg + $dstb * $dstb);
					}
					
					echo '<td style="background:#'.sprintf('%2x%2x%2x', $ixColumnR[$i], $ixColumnG[$i], $ixColumnB[$i]).'">' . $i . '<hr />'.round($dstprev).'</td>';
				}
			    echo '<tr></table>';
			    
			    */
			    
			    //find left border
			    for($i = 0; $i < $imw; $i++)
			    {
			    	$dstprev = 0;
			    	if($i > 0)
					{
						$dstr = $ixColumnR[$i] - $ixColumnR[$i-1];
						$dstg = $ixColumnG[$i] - $ixColumnG[$i-1];
						$dstb = $ixColumnB[$i] - $ixColumnB[$i-1];
						$dstprev = sqrt($dstr * $dstr + $dstg * $dstg + $dstb * $dstb);
					}
					
					//thredshold = 100 to detect edge from previous color
					if($dstprev > 100)
					{
						$xmin = $i;
						break;
					}
				}
				
				//find right border
				for($i = $imw-1; $i >= 0; $i--)
			    {

			    	$dstnext = 0;
			    	if($i < $imw - 1)
					{
						$dstr = $ixColumnR[$i] - $ixColumnR[$i+1];
						$dstg = $ixColumnG[$i] - $ixColumnG[$i+1];
						$dstb = $ixColumnB[$i] - $ixColumnB[$i+1];
						$dstnext = sqrt($dstr * $dstr + $dstg * $dstg + $dstb * $dstb);
					}
					
					//thredshold = 100 to detect edge from previous color
					if($dstnext > 100 && $xmax == 0)
					{
						$xmax = $i;
					}
				}
				
				
				$ymin = 0;
				$ymax = $imh - 1;
				//echo '<h1>XMIN:' . $xmin . ', XMAX: ' . $xmax.'</h1>';
			    
			    
			    // The new width and height of the image. (not including padding)
			    $imw = 1+ $xmax-$xmin - 2; // Image width in pixels
			    $imh = 1+ $ymax-$ymin - 2; // Image height in pixels
			    
			    
			    // Make another image to place the trimmed version in.
    			$im2 = imagecreatetruecolor($imw, $imh);
    			
    			// Copy it over to the new image.
    			if(!imagecopy($im2, $im, 0, 0, $xmin+1, $ymin+1, $imw, $imh))
    			{
    				
    				$result = false;
				}
				else
				{
					//var_dump($im2, $imagepath);
					//exit();
					//OUTPUT IMAGE
    				switch($ext)
				    {
		    			case 'JPG': imagejpeg($im2, $imagepath); break;
		    			case 'GIF': imagegif($im2, $imagepath); break;
		    			case 'PNG': imagepng($im2, $imagepath); break;
					}
					
					$result = true;
				}
				
				imagedestroy($im);
				imagedestroy($im2);
			}
		}
		
		return $result;
	}
	
	
	/**
	* Kiem tra neu url khong bat dau bang http://
	* 
	* @param mixed $url
	*/
	public static function paddingWebsitePrefix($url)
	{
		if(strpos($url, 'http') !== 0)
		{
			$url = 'http://' . $url;
		}
		
		return $url;
	}
	
	/**
	* Boi vi he thong goi mail khong nhan duoc tieng viet trong fullname
	* nen xu ly fullname truoc khi goi email
	* 
	* @param mixed $fullname
	*/
	public static function refineEmailSendername($fullname)
	{
		$fullname = ucwords(Helper::codau2khongdau($fullname));
		
		//trademark character will error when sending
		//so convert to htmlentity before sending
		$fullname = htmlentities($fullname);
		
		return $fullname;
	}
	
	public static function isNhuan($year)
	{
		return ($year % 400 == 0 || ($year % 4 == 0 && $year % 100 != 0));
		
	}
	
	/**
	* Loai bo ky tu khogn can thiet de chong XSS
	* Loai bo HTML tag, chi giu lai cac ky tu binh thuong, ko format
	* 
	* @param mixed $s
	*/
	public static function plaintext($s)
	{
		$s = strip_tags($s);
		$s = self::xss_clean($s);
		
		return $s;
	}
	
	
		
	
	function relative_datetime($timestamp)
	{
		global $lang;
		
		if(!$timestamp){
			return 'N/A';
		}
	 
		$difference = time() - $timestamp;
		$periods = array($lang['global']['timeSec'], $lang['global']['timeMin'], $lang['global']['timeHour'], $lang['global']['timeDay'], $lang['global']['timeWeek'],$lang['global']['timeMonth'], $lang['global']['timeYear']);
		$lengths = array("60","60","24","7","4.35","12");
		$total_lengths = count($lengths);
	 
		if ($difference > 0) { // this was in the past
			$ending = $lang['global']['timeAgo'];
		} else if($difference == 0)
		{
			return $lang['global']['timeSeveralSec'];
		}
		else { // this was in the future
			$difference = -$difference;
			$ending = $lang['global']['timeFromNow'];
		}
		//return;
	 
		for($j = 0; $difference > $lengths[$j] && $total_lengths > $j; $j++) {
			$difference /= $lengths[$j];
		}
	 
		$difference = round($difference);
		if($difference != 1) {
			$periods[$j].= $lang['global']['timePlural'];
		}
	 
		$text = "$difference $periods[$j] $ending";
	 
		return $text;
	}
	
	function isValidURL($url)
	{
		return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
	}
	
	function check_user_agent ( $type = NULL ) 
	{
        $user_agent = strtolower ( $_SERVER['HTTP_USER_AGENT'] );
        if ( $type == 'bot' ) {
                // matches popular bots
                if ( preg_match ( "/googlebot|adsbot|yahooseeker|yahoobot|msnbot|watchmouse|pingdom\.com|feedfetcher-google/", $user_agent ) ) {
                        return true;
                        // watchmouse|pingdom\.com are "uptime services"
                }
        } else if ( $type == 'browser' ) {
                // matches core browser types
                if ( preg_match ( "/mozilla\/|opera\//", $user_agent ) ) {
                        return true;
                }
        } else if ( $type == 'mobile' ) {
                // matches popular mobile devices that have small screens and/or touch inputs
                // mobile devices have regional trends; some of these will have varying popularity in Europe, Asia, and America
                // detailed demographics are unknown, and South America, the Pacific Islands, and Africa trends might not be represented, here
                if ( preg_match ( "/phone|iphone|itouch|ipod|symbian|android|htc_|htc-|palmos|blackberry|opera mini|iemobile|windows ce|nokia|fennec|hiptop|kindle|mot |mot-|webos\/|samsung|sonyericsson|^sie-|nintendo/", $user_agent ) ) {
                        // these are the most common
                        return true;
                } else if ( preg_match ( "/mobile|pda;|avantgo|eudoraweb|minimo|netfront|brew|teleca|lg;|lge |wap;| wap /", $user_agent ) ) {
                        // these are less common, and might not be worth checking
                        return true;
                }
        }
        return false;
	}
	
	/**
	* Dung cho truong hop lay url hien tai hoac do change controllergroup trong chuc nang move giua site <-> mobile version
	* 
	* @param mixed $newControllerGroup
	*/
	public function getCurrentUrl($newControllerGroup = '')
	{
		global $registry;
		
		$originalPath = $_SERVER['REQUEST_URI'];
		
		$parts = explode('/', $originalPath);
		
		//if change controllerGroup, replace with new controllergroup
		if($newControllerGroup != $parts[1] && $newControllerGroup != '')
			$parts[1] = $newControllerGroup;
			
		//remove first slash character
		array_shift($parts);
			
		$newPath = $registry->conf['rooturl'] . implode('/', $parts);
		
		//add force url
		$newPath .= '?forceversion=' . $newControllerGroup;
		
		return $newPath;
		
	}
	
	

}  



	

?>