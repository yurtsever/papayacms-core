<?php
/**
* Interface for classes that allow to convert a variable to a DOMElement
* values tree usable by the template engines
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
* @subpackage Template
* @version $Id: Loadable.php 37646 2012-11-07 14:28:32Z weinert $
*/

/**
* Interface for classes that allow to convert a variable to a DOMElement
* values tree usable by the template engines.
*
* @property PapayaObjectOptionsList $parameters
* @property PapayaObjectList $loaders
* @property DOMDocument $values
*
* @package Papaya-Library
* @subpackage Template
*/
interface PapayaTemplateEngineValuesLoadable {

  /**
  * @param mixed $values
  * @return FALSE|PapayaXmlElement|PapayaXmlDocument
  */
  function load($values);

}