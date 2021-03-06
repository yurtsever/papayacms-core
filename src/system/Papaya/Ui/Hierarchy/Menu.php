<?php
/**
* A hierarchy menu is used to show a line of links representing the current hierarchy of data.
*
* @copyright 2011 by papaya Software GmbH - All rights reserved.
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
* @version $Id: Menu.php 36591 2011-12-30 11:25:33Z weinert $
*/

/**
* A hierarchy menu is used to show a line of links representing the current hierarchy of data.
*
* @package Papaya-Library
* @subpackage Ui
*
* @property PapayaUiHierarchyItems $items
*/
class PapayaUiHierarchyMenu extends PapayaUiControl {

  /**
  * Items buffer variable
  *
  * @var PapayaUiHierarchyItems
  */
  private $_items = NULL;

  /**
  * Allow to assign the internal (protected) variables using a public property
  *
  * @var array
  */
  protected $_declaredProperties = array(
    'items' => array('items', 'items')
  );

  /**
  * Append menu to parent xml element
  *
  * @param PapayaXmlElement $parent
  * @return PapayaXmlElement|NULL
  */
  public function appendTo(PapayaXmlElement $parent) {
    if (count($this->items()) > 0) {
      $menu = $parent->appendElement('hierarchy-menu');
      $this->items()->appendTo($menu);
      return $menu;
    } else {
      return NULL;
    }
  }

  /**
  * Getter/Setter for the hierarchy items collection
  *
  * @param PapayaUiHierarchyItems $items
  * @return PapayaUiHierarchyItems
  */
  public function items(PapayaUiHierarchyItems $items = NULL) {
    if (isset($items)) {
      $this->_items = $items;
    } elseif (is_null($this->_items)) {
      $this->_items = new PapayaUiHierarchyItems();
      $this->_items->owner($this);
    }
    return $this->_items;
  }
}