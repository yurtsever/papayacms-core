<?php
/**
* Provides several links to navigate to previous pages of a list in a listview. This
* output links to pages with a lower number.
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
* @version $Id: Down.php 36336 2011-10-20 13:58:07Z weinert $
*/

/**
* Provides several links to navigate to previous pages of a list in a listview. This
* output links to pages with a lower number.
*
* @package Papaya-Library
* @subpackage Ui
*/
class PapayaUiListviewItemPagingDown extends PapayaUiListviewItemPaging {

  protected $_image = 'actions-go-previous';

  /**
  * Provide pages with a lower page number than the current page
  *
  * @return array
  */
  public function getPages() {
    $minimum = $this->getCurrentPage() - $this->_pageLimit;
    $maximum = $this->getCurrentPage();
    if ($minimum < 1) {
      $minimum = 1;
    }
    $pages = array();
    for ($i = $minimum; $i < $maximum; ++$i) {
      $pages[] = $i;
    }
    return $pages;
  }

  /**
  * Return the page that will be used for the image link
  *
  * @return integer
  */
  public function getImagePage() {
    $previous = $this->getCurrentPage() - 1;
    return ($previous < 1) ? 1 : $previous;
  }
}