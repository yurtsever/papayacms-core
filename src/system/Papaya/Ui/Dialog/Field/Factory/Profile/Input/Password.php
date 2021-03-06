<?php
/**
* Field factory profiles for a input for a password.
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
* @version $Id: Password.php 37469 2012-08-24 13:35:41Z weinert $
*/

/**
* Field factory profiles for a input for a password.
*
* @package Papaya-Library
* @subpackage Ui
*/
class PapayaUiDialogFieldFactoryProfileInputPassword extends PapayaUiDialogFieldFactoryProfile {

  /**
   * @see PapayaUiDialogFieldFactoryProfile::getField()
   * @return PapayaUiDialogFieldInput
   */
  public function getField() {
    $field = new PapayaUiDialogFieldInputPassword(
      $this->options()->caption,
      $this->options()->name,
      (int)$this->options()->parameters,
      $this->options()->validation
    );
    if ($hint = $this->options()->hint) {
      $field->setHint($hint);
    }
    return $field;
  }
}