<?php
/**
* Message string context containing a file
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
* @subpackage Messages
* @version $Id: File.php 36250 2011-09-28 12:41:07Z zerebecki $
*/

/**
* Message string context containing a file
*
* The line and file positions start by 1 - not by zero.
*
* @package Papaya-Library
* @subpackage Messages
*/
class PapayaMessageContextFile
  implements
    PapayaMessageContextInterfaceList,
    PapayaMessageContextInterfaceString,
    PapayaMessageContextInterfaceXhtml {

  protected $_fileName = '';
  protected $_line = 0;
  protected $_column = 0;

  /**
  * Create file contents by name and optional position
  *
  * @param string $fileName
  * @param integer $line
  * @param integer $column
  */
  public function __construct($fileName, $line = 0, $column = 0) {
    $this->_fileName = $fileName;
    if ($line >= 0) {
      $this->_line = (int)$line;
      $this->_column = ($column >= 0) ? (int)$column : 0;
    }
  }

  /**
  * Validate if the given file is readable
  *
  * @param string $fileName
  * @return boolean
  */
  public function readable($fileName) {
    if (file_exists($fileName) && is_file($fileName) && is_readable($fileName)) {
      return TRUE;
    }
    return FALSE;
  }

  /**
  * Return file contents as plain text lines, remove whitespaces from the line ends
  *
  * @return array
  */
  public function asArray() {
    if ($this->readable($this->_fileName)) {
      $lines = file($this->_fileName);
      return array_map('chop', $lines);
    }
    return array();
  }

  /**
  * Output file contents as a ordered list, highlighting file position if available
  *
  * @return string
  */
  public function asXhtml() {
    $result = '';
    if ($this->readable($this->_fileName)) {
      $lines = file($this->_fileName);
      if (count($lines) > 0) {
        $result .= '<ol class="file" style="white-space: pre; font-family: monospace;">';
        foreach ($lines as $index => $line) {
          $line = chop($line, "\r\n");
          if ($index == ($this->_line - 1)) {
            $split = ($this->_column - 1) > 0 ? $this->_column - 1 : 0;
            $offsetContent = substr($line, 0, $split);
            $highlightContent = substr($line, $split);
            $result .= sprintf(
              '<li style="list-style-position: outside;">'.
              '<strong>%s<em>%s</em></strong></li>',
              PapayaUtilStringXml::escape($offsetContent),
              PapayaUtilStringXml::escape($highlightContent)
            );
          } else {
            $result .= sprintf(
              '<li style="list-style-position: outside;">%s</li>',
              PapayaUtilStringXml::escape($line)
            );
          }
        }
        $result .= '</ol>';
      }
    }
    return $result;
  }

  /**
  * Return file contents wihtout changes
  *
  * @return string
  */
  public function asString() {
    if ($this->readable($this->_fileName)) {
      return file_get_contents($this->_fileName);
    } else {
      return '';
    }
  }

  /**
  * Provides a filename and position as a label/title
  *
  * @return string
  */
  public function getLabel() {
    $result = $this->_fileName;
    if ($this->_line > 0) {
      $result .= ':'.((int)$this->_line);
      if ($this->_column > 0) {
        $result .= ':'.((int)$this->_column);
      }
    }
    return $result;
  }
}
