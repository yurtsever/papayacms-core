<?php
/**
* Abstract superclass for ui commands, like executing a dialog.
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
* @subpackage Ui
* @version $Id: Command.php 39721 2014-04-07 13:13:23Z weinert $
*/

/**
* Abstract superclass for ui commands, like executing a dialog.
*
* @package Papaya-Library
* @subpackage Ui
*/
abstract class PapayaUiControlCommand extends PapayaUiControlInteractive {

  /**
  * A permission that is validated for the current administration user,
  * before executing the command
  *
  * @var NULL|array|integer
  */
  private $_permission = NULL;

  /**
  * A condition that is validated, before executing the command
  *
  * @var NULL|TRUE|PapayaUiControlCommandCondition
  */
  private $_condition = NULL;

  /**
  * The owner of the command. This is where the command gets it parameters from.
  *
  * @param PapayaUiControlInteractive
  */
  private $_owner = NULL;

  /**
  * Validate if the command has to be executed. Can return a boolean or throw an exception.
  *
  * @return boolean
  */
  public function validateCondition() {
    $condition = $this->condition();
    if ($condition instanceof PapayaUiControlCommandCondition) {
      return $condition->validate();
    } else {
      return (bool)$condition;
    }
  }

  /**
  * Condition can be used to validate if an command can be executed.
  *
  * @param PapayaUiControlCommandCondition $condition
  * @return TRUE|PapayaUiControlCommandCondition
  */
  public function condition(PapayaUiControlCommandCondition $condition = NULL) {
    if (isset($condition)) {
      $this->_condition = $condition;
      $this->_condition->command($this);
    } elseif (is_null($this->_condition)) {
      $this->_condition = $this->createCondition();
    }
    return $this->_condition;
  }

  /**
  * The default condition is just the boolean value TRUE.
  *
  * @return TRUE|PapayaUiControlCommandCondition
  */
  public function createCondition() {
    return TRUE;
  }

  /**
  * Validate the assigned permission.
  *
  * @throws UnexpectedValueException
  * @return boolean
  */
  public function validatePermission() {
    if ($permission = $this->permission()) {
      if (is_array($permission) && count($permission) == 2) {
        $user = $this->papaya()->administrationUser;
        return $user->hasPerm($permission[1], $permission[0]);
      } elseif (is_integer($permission)) {
        $user = $this->papaya()->administrationUser;
        return $user->hasPerm($permission);
      } else {
        throw new UnexpectedValueException(
          'UnexpectedValueException: Invalid permission value.'
        );
      }
    }
    return TRUE;
  }

  /**
   * Getter/Setter for the permission
   *
   * @param int $permission
   * @return NULL|array|integer
   */
  public function permission($permission = NULL) {
    if (isset($permission)) {
      $this->_permission = $permission;
    }
    return $this->_permission;
  }

  /**
  * Assign a owner control to the command, the command reads parameters and the application
  * object from the owner.
  *
  * If the owner is emtpy and exception is thrown.
  *
  * @throws LogicException
  * @param PapayaRequestParametersInterface|NULL $owner
  * @return PapayaRequestParametersInterface
  */
  public function owner(PapayaRequestParametersInterface $owner = NULL) {
    if (isset($owner)) {
      $this->_owner = $owner;
      $this->papaya($owner->papaya());
    } elseif (is_null($this->_owner)) {
      throw new LogicException(
        sprintf(
          'LogicException: Instance of "%s" has no owner assigned.',
          get_class($this)
        )
      );
    }
    return $this->_owner;
  }

  /**
  * Validate if an owner object is assigned
  *
  * @return boolean
  */
  public function hasOwner() {
    return isset($this->_owner);
  }

  /**
  * Get/Set parameter handling method. This will be used to define the parameter sources.
  *
  * If an owner is available, its parameterMethod function will be used.
  *
  * @param integer $method
  * @return integer
  */
  public function parameterMethod($method = NULL) {
    if ($this->hasOwner()) {
      $method = $this->owner()->parameterMethod($method);
    }
    return parent::parameterMethod($method);
  }

  /**
  * Get/Set the parameter group name.
  *
  * This puts/expects all parameters into/in a parameter group.
  * If an owner is available, its parameterGroup function will be used.
  *
  * @param string|NULL $groupName
  * @return string|NULL
  */
  public function parameterGroup($groupName = NULL) {
    if ($this->hasOwner()) {
      $groupName = $this->owner()->parameterGroup($groupName);
    }
    return parent::parameterGroup($groupName);
  }

  /**
  * Access request parameters
  *
  * This method gives you access to request parameters.
  * If an owner is available, its parameters function will be used.
  *
  * @param PapayaRequestParameters $parameters
  * @return PapayaRequestParameters
  */
  public function parameters(PapayaRequestParameters $parameters = NULL) {
    if ($this->hasOwner()) {
      $parameters = $this->owner()->parameters($parameters);
    }
    return parent::parameters($parameters);
  }
}