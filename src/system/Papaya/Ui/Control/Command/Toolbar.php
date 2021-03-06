<?php
/**
* A command that adds elements to a provided toolbar, this will not add elements to the DOM but
* the papayaUI toolbar obkject.
*
* @copyright 2013 by papaya Software GmbH - All rights reserved.
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
* @version $Id: Toolbar.php 39585 2014-03-17 13:51:15Z weinert $
*/

/**
* A command that adds elements to a provided toolbar, this will not add elements to the DOM but
* the papayaUI toolbar obkject.
*
* @package Papaya-Library
* @subpackage Ui
*/
abstract class PapayaUiControlCommandToolbar extends PapayaUiControlCommand {

  /**
   * @var PapayaUiToolbarElements
   */
  private $_elements = NULL;

  /**
   * Append the elements to the provided toolbar, buttons, dropdowns, ...
   */
  abstract public function appendToolbarElements();

  /**
   * @param PapayaUiToolbarElements $elements
   */
  public function __construct(PapayaUiToolbarElements $elements) {
    $this->elements($elements);
  }

  /**
   * Getter/Setter for the toolbar elements
   *
   * @param PapayaUiToolbarElements $elements
   * @return PapayaUiToolbarElements
   */
  public function elements(PapayaUiToolbarElements $elements = NULL) {
    if (isset($elements)) {
      $this->_elements = $elements;
    }
    return $this->_elements;
  }

  /**
   * appendTo is used as an trigger only - it actually does not modify the dom.
   *
   * @param PapayaXmlElement $parent
   * @return PapayaXmlElement
   */
  public function appendTo(PapayaXmlElement $parent) {
    $this->appendToolbarElements();
    return $parent;
  }
}