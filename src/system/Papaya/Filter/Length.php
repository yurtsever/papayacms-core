<?php
/**
* Papaya filter class for an string length
*
* @copyright 2014 by papaya Software GmbH - All rights reserved.
* @link http://www.papaya-cms.com/
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, version 2
*
* You can redistribute and/or modify this script under the terms of the GNU General Public
* License (GPL) version 2, provided that the copyright and license notes, including these
* lines, remain unmodified. papaya is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.
*
* @package Papaya-Library
* @subpackage Filter
* @version $Id: Length.php 39468 2014-02-28 19:51:17Z weinert $
*/

/**
* Papaya filter class for an string length
*
* It can be used to validate if a given input is an integer number with
* or without a sign. Additionally minimum and maximum limits can be set
* for the number.
*
* The filter function will cast the value to integer.
*
* @package Papaya-Library
* @subpackage Filter
*/
class PapayaFilterLength implements PapayaFilter {

  /**
  * Minimum limit for integer value
  * @var integer
  */
  private $_minimum = NULL;
  /**
  * Maximum limit for integer value
  * @var integer
  */
  private $_maximum = NULL;

  /**
   * @var bool use sttring as utf-8 and return the codepoint count
   */
  private $_isUtf8 = FALSE;

  /**
   * Construct object and initialize minimum and maximum limits for the integer value
   *
   * @param integer|NULL $minimum
   * @param integer|NULL $maximum
   * @param bool $isUtf8
   * @throws RangeException
   */
  public function __construct($minimum = 0, $maximum = NULL, $isUtf8 = FALSE) {
    $this->_minimum = (int)$minimum;
    if (isset($maximum)) {
      if ($maximum < $minimum) {
        throw new RangeException('The maximum needs to be larger then the minimum.');
      }
      $this->_maximum = (int)$maximum;
    }
    $this->_isUtf8 = (bool)$isUtf8;
  }

  /**
  * Check the input and throw an exception if it does not match the condition.
  *
  * @throws PapayaFilterException
  * @param string $value
  * @return TRUE
  */
  public function validate($value) {
    if ($this->_isUtf8) {
      $string = new PapayaStringUtf8(
        PapayaUtilStringUtf8::ensure($value)
      );
      $length = $string->length();
    } else {
      $length = strlen($value);
    }
    if (isset($this->_minimum) && $length < $this->_minimum) {
      throw new PapayaFilterExceptionLengthMinimum($this->_minimum, $value);
    }
    if (isset($this->_maximum) && $length > $this->_maximum) {
      throw new PapayaFilterExceptionLengthMaximum($this->_minimum, $value);
    }
    return TRUE;
  }

  /**
  * The filter function is used to read a input value if it is valid.
  *
  * @param string $value
  * @return string|NULL
  */
  public function filter($value) {
    $value = PapayaUtilStringUtf8::ensure($value);
    try {
      $this->validate($value);
      return $value;
    } catch (PapayaFilterException $e) {
      return NULL;
    }
  }
}