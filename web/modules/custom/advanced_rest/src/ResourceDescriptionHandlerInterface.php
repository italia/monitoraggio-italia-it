<?php

namespace Drupal\advanced_rest;

/**
 * Interface ResourceDescriptionHandlerInterface.
 *
 * @package Drupal\advanced_rest
 */
interface ResourceDescriptionHandlerInterface {

  /**
   * Gets all available Rest Resource Descriptions.
   *
   * @return array
   *   An array whose keys are resource names and whose corresponding values
   *   are arrays containing the resource parameter typed data structures.
   */
  public function getDescriptions();

  /**
   * Load resource description.
   *
   * @param string $description_name
   *   The description name.
   *
   * @return array|null
   *   The description array or null.
   */
  public function loadDescription($description_name);

  /**
   * Determines whether a module provides resource descriptions.
   *
   * @param string $module_name
   *   The module name.
   *
   * @return bool
   *   Returns TRUE if the module provides resource descriptions or FALSE.
   */
  public function moduleProvidesDescriptions($module_name);

}
