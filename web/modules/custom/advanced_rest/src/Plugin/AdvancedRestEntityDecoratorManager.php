<?php

namespace Drupal\advanced_rest\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Advanced Rest Entity Decorator plugin manager.
 */
class AdvancedRestEntityDecoratorManager extends DefaultPluginManager {

  /**
   * Constructor for AdvancedRestEntityDecoratorManager objects.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct(
      'Plugin/rest/EntityDecorator',
      $namespaces,
      $module_handler,
      'Drupal\advanced_rest\Plugin\AdvancedRestEntityDecoratorInterface',
      'Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator');

    $this->setCacheBackend($cache_backend, 'advanced_rest_entity_decorator_plugins');
    $this->alterInfo('advanced_rest_entity_decorator');
  }

}
