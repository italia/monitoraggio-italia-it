<?php

namespace Drupal\advanced_rest\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Entity\ContentEntityBase;

/**
 * Defines an interface for Advanced Rest Entity Decorator plugins.
 */
interface AdvancedRestEntityDecoratorInterface extends PluginInspectionInterface {

  /**
   * Set Entity.
   *
   * @param \Drupal\Core\Entity\ContentEntityBase $entity
   *   A content entity instance.
   *
   * @throws \InvalidArgumentException
   *   Throws exception expected.
   */
  public function setEntity(ContentEntityBase $entity);

  /**
   * Get Entity.
   *
   * @return \Drupal\Core\Entity\ContentEntityBase
   *   A content entity instance.
   *
   * @throws \RuntimeException
   *   Throws exception expected.
   */
  public function getEntity();

  /**
   * Decoration function.
   *
   * @return mixed
   *   An array of values for the given entity.
   */
  public function toArray();

  /**
   * Get attribute value.
   *
   * @param string $key
   *   The attribute key.
   * @param int|null $index
   *   The attribute index.
   *
   * @return mixed
   *   The attribute value.
   *
   * @throws \InvalidArgumentException
   *   Throws exception expected.
   */
  public function get($key, $index = NULL);

  /**
   * Get referenced entities decorated.
   *
   * @param string $field_name
   *   The machine name of the entity reference field to be used.
   * @param int|null $index
   *   The index of the referenced entity.
   *
   * @return array
   *   An array of referenced entities.
   *
   * @throws \InvalidArgumentException
   *   Throws exception expected.
   */
  public function getDecoratedReferencedEntities($field_name, $index = NULL);

}
