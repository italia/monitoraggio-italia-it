<?php

namespace Drupal\advanced_rest;

use Drupal\Core\Entity\ContentEntityBase;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class AdvancedRestEntityDecoratorFactory.
 *
 * @package Drupal\advanced_rest
 */
class AdvancedRestEntityDecoratorFactory implements AdvancedRestEntityDecoratorFactoryInterface {

  use ContainerAwareTrait;

  /**
   * An array of entity decorators.
   *
   * @var array
   */
  protected $decorators = [];

  /**
   * The Rest Entity decorator plugin manager.
   *
   * @var \Drupal\advanced_rest\Plugin\AdvancedRestEntityDecoratorManager
   */
  protected $entityDecoratorManager;

  /**
   * {@inheritdoc}
   */
  public function get(ContentEntityBase $entity) {
    $this->loadDefinitions();
    $decoratorManager = $this->getEntityDecoratorManager();
    $entity_type = $entity->getEntityTypeId();
    $bundle = $entity->bundle();

    // Search for bundle specific decorator if exists, otherwise try to find the
    // most generic entity decorator.
    $keys = [
      $entity_type . '-' . $bundle,
      $entity_type,
      'entity',
    ];

    foreach ($keys as $key) {
      if (!isset($this->decorators[$key])) {
        continue;
      }

      // If the decorator value is not an object, we need to create the plugin
      // instance.
      if (!is_object($this->decorators[$key]) && $decoratorManager->hasDefinition($this->decorators[$key])) {
        $decorator = $decoratorManager->createInstance($this->decorators[$key]);
        $this->decorators[$key] = $decorator;
      }

      $this->decorators[$key]->setEntity($entity);
      return $this->decorators[$key];
    }

    $message = sprintf('Missing Decorator for entity "%s"', $entity_type);
    throw new \RuntimeException($message);
  }

  /**
   * Loads decorators definitions list.
   *
   * Builds a decorators definitions list in the form of an associative array,
   * where the keys are the concatenation of entity type and bundle properties,
   * and values are only the plugin definition id. This results in a sort of
   * lazy loading of plugin instances, which will be created only if necessary.
   */
  protected function loadDefinitions() {
    if (!empty($this->decorators)) {
      return;
    }

    $decoratorManager = $this->getEntityDecoratorManager();
    foreach ($decoratorManager->getDefinitions() as $definition) {
      $keys = array_filter([
        $definition['entityType'],
        $definition['bundle'],
      ]);
      $key = implode('-', $keys);
      $this->decorators[$key] = $definition['id'];
    }
  }

  /**
   * Get Rest Entity Decorator Plugin Manager.
   *
   * @return \Drupal\advanced_rest\Plugin\AdvancedRestEntityDecoratorManager
   *   The Rest Entity Decorator Plugin Manager.
   */
  protected function getEntityDecoratorManager() {
    if (!isset($this->entityDecoratorManager)) {
      $id = 'plugin.manager.advanced_rest.entity_decorator';
      $this->entityDecoratorManager = $this->container->get($id);
    }
    return $this->entityDecoratorManager;
  }

}
