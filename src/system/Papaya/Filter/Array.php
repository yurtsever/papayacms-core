<?php
/**
* Papaya filter class that validates an array and optionally each element of the array
*
* @copyright 2010 by papaya Software GmbH - All rights reserved.
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
* @version $Id: Array.php 38143 2013-02-19 14:58:24Z weinert $
*/

/**
* Papaya filter class that validates an array and optionally each element of the array
*
* The filter function will return the element rather then the input.
*
* @package Papaya-Library
* @subpackage Filter
*/
class PapayaFilterArray implements PapayaFilter {

  /**
  * elements filter
  * @var PapayaFilter|NULL
  */
  private $_elementFilter = NULL;

  /**
  * Construct object and filter for the elements
  *
  * @param PapayaFilter|NULL $elementFilter
  */
  public function __construct(PapayaFilter $elementFilter = NULL) {
    $this->_elementFilter = $elementFilter;
  }

  /**
  * Check if the value is an array and if an element filter is set, check each element against it.
  *
  * @throws PapayaFilterException
  * @param string $value
  * @return TRUE
  */
  public function validate($value) {
    if (!(is_array($value) && count($value) > 0)) {
      throw new PapayaFilterExceptionEmpty();
    }
    if (isset($this->_elementFilter)) {
      foreach ($value as $element) {
        $this->_elementFilter->validate($element);
      }
    }
    return TRUE;
  }

  /**
  * Return the value aus an array, if the element filter ist set only return elements after
  * filtering them.
  *
  * @param string $value
  * @return integer|NULL
  */
  public function filter($value) {
    $result = NULL;
    if (is_array($value) && !empty($value)) {
      if (isset($this->_elementFilter)) {
        $result = array();
        foreach ($value as $key => $element) {
          if (NULL !== ($elementValue = $this->_elementFilter->filter($element))) {
            $result[$key] = $elementValue;
          }
        }
      } else {
        $result = $value;
      }
    }
    return empty($result) ? NULL : $result;
  }
}