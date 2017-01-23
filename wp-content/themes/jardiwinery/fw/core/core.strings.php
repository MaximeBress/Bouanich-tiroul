<?php
/**
 * JardiWinery Framework: strings manipulations
 *
 * @package	jardiwinery
 * @since	jardiwinery 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'JARDIWINERY_MULTIBYTE' ) ) define( 'JARDIWINERY_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('jardiwinery_strlen')) {
	function jardiwinery_strlen($text) {
		return JARDIWINERY_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('jardiwinery_strpos')) {
	function jardiwinery_strpos($text, $char, $from=0) {
		return JARDIWINERY_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('jardiwinery_strrpos')) {
	function jardiwinery_strrpos($text, $char, $from=0) {
		return JARDIWINERY_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('jardiwinery_substr')) {
	function jardiwinery_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = jardiwinery_strlen($text)-$from;
		}
		return JARDIWINERY_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('jardiwinery_strtolower')) {
	function jardiwinery_strtolower($text) {
		return JARDIWINERY_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('jardiwinery_strtoupper')) {
	function jardiwinery_strtoupper($text) {
		return JARDIWINERY_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('jardiwinery_strtoproper')) {
	function jardiwinery_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<jardiwinery_strlen($text); $i++) {
			$ch = jardiwinery_substr($text, $i, 1);
			$rez .= jardiwinery_strpos(' .,:;?!()[]{}+=', $last)!==false ? jardiwinery_strtoupper($ch) : jardiwinery_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('jardiwinery_strrepeat')) {
	function jardiwinery_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('jardiwinery_strshort')) {
	function jardiwinery_strshort($str, $maxlength, $add='...') {
		if ($maxlength < 0) 
			return $str;
		if ($maxlength == 0) 
			return '';
		if ($maxlength >= jardiwinery_strlen($str)) 
			return strip_tags($str);
		$str = jardiwinery_substr(strip_tags($str), 0, $maxlength - jardiwinery_strlen($add));
		$ch = jardiwinery_substr($str, $maxlength - jardiwinery_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = jardiwinery_strlen($str) - 1; $i > 0; $i--)
				if (jardiwinery_substr($str, $i, 1) == ' ') break;
			$str = trim(jardiwinery_substr($str, 0, $i));
		}
		if (!empty($str) && jardiwinery_strpos(',.:;-', jardiwinery_substr($str, -1))!==false) $str = jardiwinery_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('jardiwinery_strclear')) {
	function jardiwinery_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (jardiwinery_substr($text, 0, jardiwinery_strlen($open))==$open) {
					$pos = jardiwinery_strpos($text, '>');
					if ($pos!==false) $text = jardiwinery_substr($text, $pos+1);
				}
				if (jardiwinery_substr($text, -jardiwinery_strlen($close))==$close) $text = jardiwinery_substr($text, 0, jardiwinery_strlen($text) - jardiwinery_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('jardiwinery_get_slug')) {
	function jardiwinery_get_slug($title) {
		return jardiwinery_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('jardiwinery_strmacros')) {
	function jardiwinery_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('jardiwinery_unserialize')) {
	function jardiwinery_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = @unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			return $data;
		} else
			return $str;
	}
}
?>