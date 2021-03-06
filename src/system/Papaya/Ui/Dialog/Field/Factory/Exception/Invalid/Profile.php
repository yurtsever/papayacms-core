<?php
/**
* The profile name is invalid, no mapping or profile class was found.
*
* @copyright 2012 by papaya Software GmbH - All rights reserved.
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
* @subpackage Ui
* @version $Id: Profile.php 37356 2012-08-03 14:48:07Z weinert $
*/

/**
* The profile name is invalid, no mapping or profile class was found.
*
* @package Papaya-Library
* @subpackage Ui
*/
class PapayaUiDialogFieldFactoryExceptionInvalidProfile
  extends PapayaUiDialogFieldFactoryException {

  /**
   * Create exception with compiled message
   *
   * @param string $profileName
   */
  public function __construct($profileName) {
    parent::__construct(
      sprintf('Invalid field factory profile name "%s".', $profileName)
    );
  }
}
