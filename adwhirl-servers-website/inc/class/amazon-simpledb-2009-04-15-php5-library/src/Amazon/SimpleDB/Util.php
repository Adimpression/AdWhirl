<?php
/*
 -----------------------------------------------------------------------
Copyright 2009 AdMob, Inc.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
------------------------------------------------------------------------
*/
?>
<?php
/** 
 *  PHP Version 5
 *
 *  @category    Amazon
 *  @package     Amazon_SimpleDB
 *  @copyright   Copyright 2008 Amazon Technologies, Inc.
 *  @link        http://aws.amazon.com
 *  @license     http://aws.amazon.com/apache2.0  Apache License, Version 2.0
 *  @version     2009-04-15
 */
/******************************************************************************* 
 *    __  _    _  ___ 
 *   (  )( \/\/ )/ __)
 *   /__\ \    / \__ \
 *  (_)(_) \/\/  (___/
 * 
 *  Amazon Simple DB PHP5 Library
 *  Generated: Mon May 11 15:25:08 PDT 2009
 * 
 */
/**
 * Amazon_SimpleDB_Util
 * Provides collection of static functions for conversion of various values into strings that may be 
 * compared lexicographically.
 */ 
class Amazon_SimpleDB_Util
{

	/**
	 * Encodes a positive integer or floating point value into a string by zero-padding number up to the specified number of digits.
	 * 
	 * @param integer|float positive integer or floating point value to be encoded
	 * @param integer maximum number of digits in the largest value in the data set
	 * @return string representation of the zero-padded integer or floating point value
	 */
	public static function encodeZeroPadding($number, $maxNumDigits) {
		$number = strval($number);
		if (($dec = strpos($number,".")) === FALSE) {
			return(str_pad($number,$maxNumDigits,"0",STR_PAD_LEFT));
		} else {
			return(str_pad($number,$maxNumDigits + (strlen($number) - $dec),"0",STR_PAD_LEFT));
		}
	}
	
	/**
	 * Decodes zero-padded positive integer value from the string representation
	 * 
	 * @param string zero-padded string representation of the integer
	 * @return integer original integer value
	 */
	public static function decodeZeroPaddingInt($value) {
		return(intval($value));
	}
	
	/**
	 * Decodes zero-padded positive float value from the string representation
	 * 
	 * @param string zero-padded string representation of the float value
	 * @return float original float value
	 */
	public static function decodeZeroPaddingFloat($value) {
		return(floatval($value));
	}
	
	/**
	 * Encodes real number value into a string by offsetting and zero-padding 
	 * number up to the specified number of digits.  Use this encoding method if the data
	 * range set includes both positive and negative values.
	 * This function handles both float and int values.
	 * 
	* @param integer|float real number to be encoded
	* @param integer maximum number of digits left of the decimal point in the largest absolute value in the data set
	* @param integer maximum number of digits right of the decimal point in the largest absolute value in the data set, i.e. precision
	* @param integer offset value, has to be greater than absolute value of any negative number in the data set.
	* @return					string representation of the integer
	*/
	public static function encodeRealNumberRange($number, $maxDigitsLeft, $maxDigitsRight = 0, $offsetValue = 0) {
		$number = round(($number+$offsetValue) * pow(10,$maxDigitsRight));
		return(str_pad($number,$maxDigitsLeft+$maxDigitsRight,"0",STR_PAD_LEFT));
	}
	
	/**
	 * Decodes integer value from the string representation that was created by using encodeRealNumberRange(..) function.
	 * 
	 * @param string representation of the integer value
	 * @param integer offset value that was used in the original encoding
	 * @return integer original integer value
	 */
	public static function decodeRealNumberRangeInt($value, $offsetValue) {
		return(intval($value) - $offsetValue);
	}
	
	/**
	 * Decodes float value from the string representation that was created by using encodeRealNumberRange(..) function.
	 * 
	 * @param string representation of the floating point value
	 * @param integer maximum number of digits left of the decimal point in the largest absolute value in the data set (must be the same as the one used for encoding).
	 * @param integer offset value that was used in the original encoding
	 * @return flaot original floating point value
	 */
	public static function decodeRealNumberRangeFloat($value, $maxDigitsRight, $offsetValue) {
		return(floatval($value) / pow(10, $maxDigitsRight) - $offsetValue);
	}
	
	/**
	 * Encodes date value into string format that can be compared lexicographically 
	 *
	 * @param float date value to be encoded (microtime format - local timezone)
	 * @return string representation of the date value
	 */
	public static function encodeDate($microtimestamp = NULL) {
		if ($microtimestamp === NULL) $microtimestamp = microtime(TRUE);
	
		/**
		 * PHP doesn't natively support milliseconds in the date() function before 5.2.2.
		 * Workaround using microtime to be safe: insert ___, replace with microtime.
		 * PHP doesn't support +HH:MM GMT offset until 5.1.1.
		 * Workaround using supported +HHMM format, and then inject ':'.
		 */
		$date = date("Y-m-d\TH:i:s.___O",floor($microtimestamp));
		$msec = round(($microtimestamp - floor($microtimestamp)) * 1000);
		$date = str_replace("___",$msec,$date);
		$date = substr($date,0,-2) . ':' . substr($date,-2);
		return($date);
	}
	
	/**
	 * Decodes date value from the string representation created using encodeDate(..) function.
	 * 
	 * @param string representation of the date value
	 * @return original date value in microtime format
	 */
	public static function decodeDate($value) {
		/**
		 * PHP's string parsing functions suffer from the same version-related
		 * issues as the encodeDate function. This is a lowest common
		 * denominator implementation. (Using strptime woudl be ideal)
		 */
		if ($value[4] . $value[7] . $value[10] . $value[13] . $value[16] . $value[19] . $value[26] != "--T::.:") {
			/* If symbols don't match the positions from the expected format, this isn't the right format. */
			return(FALSE);
		}
		$year = intval($value[0]  . $value[1] . $value[2] . $value[3]);
		$mon  = intval($value[5]  . $value[6]);
		$day  = intval($value[8]  . $value[9]);
		$hour = intval($value[11] . $value[12]);
		$min  = intval($value[14] . $value[15]);
		$sec  = intval($value[17] . $value[18]);
		$msec = intval($value[20] . $value[21] . $value[22]);
	
		/* Compare timezone to local time to return correct local time */
		$tzs = $value[23]; /* + or - */
		$tzh = intval($value[24] . $value[25]); /* Timezone offset hours. */
		$tzm = intval($value[27] . $value[28]); /* Timezone offset minutes */
		$tz = (($tzs == "-") ? -1 : 1) * (($tzh * 3600) + (60 * $tzm));
		$tz_diff = intval(date("Z")) - $tz;
	
		return(mktime($hour,$min,$sec,$mon,$day,$year) + ($msec/1000) + $tz_diff);
	}

}
