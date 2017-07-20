<?php

namespace Drupal\advanced_rest\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Dashboard Entity Decorator item annotation object.
 *
 * @see \Drupal\advanced_rest\Plugin\AdvancedRestEntityDecoratorManager
 * @see plugin_api
 *
 * @Annotation
 */
class AdvancedRestEntityDecorator extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * The Entity type name.
   *
   * @var string
   */
  public $entityType;

  /**
   * The Entity bundle name.
   *
   * @var string
   */
  public $bundle;

}
