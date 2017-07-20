<?php

namespace Drupal\dashboard_base;

/**
 * Class MachineNameHandler.
 *
 * @package Drupal\dashboard_base
 */
class MachineNameHandler {

  /**
   * This method needs to exist, but the constrain does the actual validation.
   *
   * @param string $value
   *   The input value.
   *
   * @return bool
   *   As the UniqueField constraint will do the actual validation, always
   *   return FALSE to skip validation here.
   */
  public static function exists($value) {
    return FALSE;
  }

}
