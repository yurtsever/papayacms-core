<?php
/**
* Papaya filter class validating a file path/directory string
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
* @version $Id: Path.php 37415 2012-08-16 10:19:59Z weinert $
*/

/**
* Papaya filter class validating a file path/directory string
*
* @package Papaya-Library
* @subpackage Filter
*/
class PapayaFilterFilePath extends PapayaFilterPcre {

  /**
   * set pattern in superclass constructor
   */
  public function __construct() {
    parent::__construct('(^([a-zA-Z]:/)?([\.a-zA-Z0-9/_~-]*/)+$)uD');
  }
}
