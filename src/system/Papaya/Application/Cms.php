<?php
/**
 * A pseudoclass extending the PapayaApplication service locator that
 * allows to declare the profiles as properties
*
* @copyright 2009 by papaya Software GmbH - All rights reserved.
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
* @subpackage Objects
* @version $Id: Cms.php 39780 2014-05-05 15:38:45Z weinert $
*/

/**
 * Class PapayaApplicationCms
 *
 * A pseudoclass extending the PapayaApplication service locator that
 * allows to declare the profiles as properties
 *
 * @property PapayaDatabaseManager $database
 * @property PapayaUiImages images
 * @property PapayaContentLanguages $languages
 * @property PapayaMessageManager $messages
 * @property PapayaConfigurationCms $options
 * @property PapayaPluginLoader $plugins
 * @property PapayaProfiler $profiler
 * @property PapayaRequest $request
 * @property PapayaResponse $response$response
 * @property PapayaSession $session
 * @property base_surfer $surfer
 * @property base_auth $administrationUser
 * @property PapayaAdministrationLanguagesSwitch $administrationLanguage
 * @property PapayaUiReferenceFactory $references
 * @property PapayaUiReferencePageFactory $pageReferences
 * @property PapayaPhrases $phrases
 */
abstract class PapayaApplicationCms extends PapayaApplication {

}