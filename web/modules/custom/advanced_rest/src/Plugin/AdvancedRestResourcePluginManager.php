<?php

namespace Drupal\advanced_rest\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Advanced rest resource plugin manager.
 */
class AdvancedRestResourcePluginManager extends DefaultPluginManager {

  /**
   * Constructor for AdvancedRestResourcePluginManager objects.
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
    parent::__construct('Plugin/rest/resource', $namespaces, $module_handler, 'Drupal\advanced_rest\Plugin\AdvancedResourceInterface', 'Drupal\rest\Annotation\RestResource');

    $this->setCacheBackend($cache_backend, 'rest_plugins');
    $this->alterInfo('advanced_rest_resource');
  }

}
