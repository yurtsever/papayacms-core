<?php
/**
* Abstract superclass for papaya template simple ast visitors.
*
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @copyright Copyright 2010-2012 PhpCss Team
*
* @package PhpCss
* @subpackage Ast
*/

/**
* Abstract superclass for papaya template simple ast visitors. This maps the
* node class names, to methods and calls them if the exists.
*
* @package PhpCss
* @subpackage Ast
*/
abstract class PapayaTemplateSimpleVisitor {

  abstract public function clear();

  /**
  * Visit an ast object
  *
  * @param PapayaTemplateSimpleAst $ast
  */
  public function visit(PapayaTemplateSimpleAst $ast) {
    if ($method = $this->getMethodName($ast, 'visit')) {
      call_user_func(array($this, $method), $ast);
    }
  }

  /**
  * Visit an ast object
  *
  * @param PapayaTemplateSimpleAst $ast
  */
  public function enter(PapayaTemplateSimpleAst $ast) {
    if ($method = $this->getMethodName($ast, 'enter')) {
      call_user_func(array($this, $method), $ast);
    }
  }

  /**
  * Visit an ast object
  *
  * @param PapayaTemplateSimpleAst $ast
  */
  public function leave(PapayaTemplateSimpleAst $ast) {
    if ($method = $this->getMethodName($ast, 'leave')) {
      call_user_func(array($this, $method), $ast);
    }
  }

  /**
   * Map the ast node class to a method name. Validate if the method exists. Return the
   * method name if the method exists or FALSE if not.
   *
   * @param PapayaTemplateSimpleAst $ast
   * @param string $prefix
   *
   * @return string|FALSE
   */
  private function getMethodName(PapayaTemplateSimpleAst $ast, $prefix = 'visit') {
    $class = get_class($ast);
    if (0 === ($p = strpos($class, 'PapayaTemplateSimpleAst'))) {
      $method = $prefix.substr($class, strlen('PapayaTemplateSimpleAst'));
    } else {
      $method = $prefix.$class;
    }
    if (method_exists($this, $method)) {
      return $method;
    }
    return FALSE;
  }
}
