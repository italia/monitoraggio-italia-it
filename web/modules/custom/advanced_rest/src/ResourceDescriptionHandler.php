<?php

namespace Drupal\advanced_rest;

use Drupal\Component\Discovery\YamlDiscovery;
use Drupal\Core\Controller\ControllerResolverInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Class ResourceDescriptionHandler.
 *
 * @package Drupal\advanced_rest
 */
class ResourceDescriptionHandler implements ResourceDescriptionHandlerInterface {
  use StringTranslationTrait;

  /**
   * The module handler.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The YAML discovery class to find all .permissions.yml files.
   *
   * @var \Drupal\Component\Discovery\YamlDiscovery
   */
  protected $yamlDiscovery;

  /**
   * The controller resolver.
   *
   * @var \Drupal\Core\Controller\ControllerResolverInterface
   */
  protected $controllerResolver;

  /**
   * All defined Rest Resource Descriptions.
   *
   * @var array
   */
  protected $descriptions;

  /**
   * Constructs a new ResourceDescriptionHandler.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation.
   * @param \Drupal\Core\Controller\ControllerResolverInterface $controller_resolver
   *   The controller resolver.
   */
  public function __construct(ModuleHandlerInterface $module_handler, TranslationInterface $string_translation, ControllerResolverInterface $controller_resolver) {
    $this->moduleHandler = $module_handler;
    $this->stringTranslation = $string_translation;
    $this->controllerResolver = $controller_resolver;
    $this->descriptions = $this->getDescriptions();
  }

  /**
   * Gets the YAML discovery.
   *
   * @return \Drupal\Component\Discovery\YamlDiscovery
   *   The YAML discovery.
   */
  protected function getYamlDiscovery() {
    if (!isset($this->yamlDiscovery)) {
      $this->yamlDiscovery = new YamlDiscovery('rest.resources', $this->moduleHandler->getModuleDirectories());
    }
    return $this->yamlDiscovery;
  }

  /**
   * {@inheritdoc}
   */
  public function getDescriptions() {
    if (empty($this->descriptions)) {
      $this->descriptions = $this->buildDescriptionsYaml();
    }

    return $this->descriptions;
  }

  /**
   * {@inheritdoc}
   */
  public function loadDescription($description_name) {
    return !empty($this->descriptions[$description_name]) ? $this->descriptions[$description_name] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function moduleProvidesDescriptions($module_name) {
    foreach ($this->getDescriptions() as $description) {
      if ($description['provider'] == $module_name) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Builds all descriptions provided by .rest.resources.yml files.
   *
   * @return array[]
   *   Each return description is an array with the following keys:
   *   - id: The Rest Resource id.
   *   - params: (mandatory) An array of resource params.
   *   - provider: The provider of the description.
   */
  protected function buildDescriptionsYaml() {
    $descriptions = [];

    foreach ($this->getYamlDiscovery()->findAll() as $provider => $resources) {
      foreach ($resources as $id => $resource) {
        if (!is_array($resource)) {
          continue;
        }
        $resource_id = "$provider.$id";
        $descriptions[$resource_id] = [
          'id' => $id,
          'provider' => $provider,
          'descriptions' => [],
        ];
        foreach ($resource as $method => $description) {
          $descriptions[$resource_id]['descriptions'][$method] = $description;
        }
      }
    }

    return $descriptions;
  }

  /**
   * Returns all module names.
   *
   * @return string[]
   *   Returns the human readable names of all modules keyed by machine name.
   */
  protected function getModuleNames() {
    $modules = [];
    foreach (array_keys($this->moduleHandler->getModuleList()) as $module) {
      $modules[$module] = $this->moduleHandler->getName($module);
    }
    asort($modules);
    return $modules;
  }

}
