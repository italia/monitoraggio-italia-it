<?php

namespace Drupal\advanced_rest;

use Drupal\Core\Entity\ContentEntityBase;

/**
 * Interface AdvancedRestEntityDecoratorFactoryInterface.
 *
 * @package Drupal\advanced_rest
 */
interface AdvancedRestEntityDecoratorFactoryInterface {

  /**
   * Retrieves the registered entity decorator for the given entity.
   *
   * @param \Drupal\Core\Entity\ContentEntityBase $entity
   *   A content entity instance.
   *
   * @return \Drupal\advanced_rest\Plugin\AdvancedRestEntityDecoratorInterface
   *   The registered entity decorator for the given entity.
   *
   * @throws \RuntimeException
   *   Throws exception expected.
   */
  public function get(ContentEntityBase $entity);

}
