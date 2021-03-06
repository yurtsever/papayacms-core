<?php
/**
* Papaya URL Transformer, transforms a absolute url to a relative url depending on conditional url
*
* @copyright 2002-2009 by papaya Software GmbH - All rights reserved.
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
* @subpackage URL
* @version $Id: Relative.php 35323 2011-01-14 10:05:32Z weinert $
*/

/**
* Papaya URL Transformer, transforms a absolute url to a relative url depending on conditional url
*
* @package Papaya-Library
* @subpackage URL
*/
class PapayaUrlTransformerRelative {

  /**
  * Transforms a absolute url to a relative url.
  * @param PapayaUrl $currentUrl current url
  * @param PapayaUrl $targetUrl url to transform
  * @return string
  */
  public function transform($currentUrl, $targetUrl) {
    if ($targetUrl->getHost() != '' &&
        $targetUrl->getScheme() == $currentUrl->getScheme() &&
        $targetUrl->getHost() == $currentUrl->getHost() &&
        $this->_comparePorts($targetUrl->getPort(), $currentUrl->getPort())) {
      if ($targetUrl->getUser() == '' ||
          $targetUrl->getUser() == $currentUrl->getUser()) {
        $path = $this->getRelativePath(
          $currentUrl->getPath(),
          $targetUrl->getPath()
        );
        if ($targetUrl->getQuery() != '') {
          $path .= '?'.$targetUrl->getQuery();
        }
        if ($targetUrl->getFragment() != '') {
          $path .= '#'.$targetUrl->getFragment();
        }
        return $path;
      }
    }
    return NULL;
  }

  /**
  * Compare two port and return TRUE if equal
  * @param string $portOne
  * @param string $portTwo
  * @return boolean
  */
  private function _comparePorts($portOne, $portTwo) {
    if ($portOne == $portTwo ||
        ($portOne == '80' && empty($portTwo)) ||
        ($portTwo == '80' && empty($portOne))) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  /**
  * Get relative path from condition to target.
  * @param string $currentPath
  * @param string $targetPath
  * @return string
  */
  public function getRelativePath($currentPath, $targetPath) {
    $parts = explode('/', $currentPath);
    array_pop($parts);
    $partCount = count($parts);
    $strippedPart = '';
    for ($i = 1; $i < $partCount; ++$i) {
      $part = $parts[$i];
      if (0 === strpos($targetPath.'/', $strippedPart.'/'.$part.'/')) {
        $strippedPart .= '/'.$part;
      } else {
        break;
      }
    }
    $result = '';
    if ($partCount - $i > 0) {
      $result = str_repeat('../', $partCount - $i);
    }
    $result .= substr($targetPath, strlen($strippedPart) + 1);
    if ($result == '') {
      return './';
    }
    return $result;
  }
}