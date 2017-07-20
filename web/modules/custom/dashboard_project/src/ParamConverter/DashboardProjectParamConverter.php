<?php

namespace Drupal\dashboard_project\ParamConverter;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\ParamConverter\ParamConverterInterface;
use Symfony\Component\Routing\Route;

/**
 * Class DashboardProjectParamConverter.
 *
 * @package Drupal\dashboard_project\ParamConverter
 */
class DashboardProjectParamConverter implements ParamConverterInterface {

  /**
   * Entity Type Manager Service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * DashboardProjectParamConverter constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entity_type_manager
   *   The Entity Type Manager Service.
   */
  public function __construct(EntityTypeManager $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function convert($value, $definition, $name, array $defaults) {
    $storage = $this->entityTypeManager->getStorage('project');
    $projects = $storage->loadByProperties(['key_unique' => $value]);
    if (!empty($projects)) {
      return reset($projects);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function applies($definition, $name, Route $route) {
    return (!empty($definition['type']) && $definition['type'] == 'dashboard:project');
  }

}
