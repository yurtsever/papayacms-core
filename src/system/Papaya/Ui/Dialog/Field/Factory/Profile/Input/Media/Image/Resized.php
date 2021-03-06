<?php
/**
* Field factory profile for a media image selection field with resize arguments.
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
* @version $Id: Resized.php 37523 2012-10-02 12:21:17Z weinert $
*/

/**
* Field factory profile for a media image selection field with resize arguments.
*
* @package Papaya-Library
* @subpackage Ui
*/
class PapayaUiDialogFieldFactoryProfileInputMediaImageResized
  extends PapayaUiDialogFieldFactoryProfile {

  /**
   * @see PapayaUiDialogFieldFactoryProfile::getField()
   * @return PapayaUiDialogFieldInputMediaImage
   */
  public function getField() {
    $field = new PapayaUiDialogFieldInputMediaImageResized(
      $this->options()->caption,
      $this->options()->name
    );
    $field->setHint($this->options()->hint);
    return $field;
  }
}