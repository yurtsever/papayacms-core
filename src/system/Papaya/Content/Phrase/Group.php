<?php
/**
* Encapsulation for phrase groups, groups allows more efficient loading for phrases
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
* @subpackage Content
* @version $Id: Group.php 39766 2014-04-28 14:24:05Z weinert $
*/

/**
* Encapsulation for phrase groups, groups allows more efficient loading for phrases
*
* @package Papaya-Library
* @subpackage Content
*/
class PapayaContentPhraseGroup extends PapayaDatabaseRecordLazy {

  /**
  * Map field names to more convinient property names
  *
  * @var array(string=>string)
  */
  protected $_fields = array(
    'id' => 'module_id',
    'title' => 'module_title',
    'identifier' => 'module_title_lower'
  );

  protected $_tableName = PapayaContentTables::PHRASE_GROUPS;


}