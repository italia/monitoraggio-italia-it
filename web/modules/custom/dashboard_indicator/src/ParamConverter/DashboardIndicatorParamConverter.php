<?php

namespace Drupal\dashboard_indicator\ParamConverter;

use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\ParamConverter\ParamConverterInterface;
use Symfony\Component\Routing\Route;

/**
 * Class DashboardIndicatorParamConverter.
 *
 * @package Drupal\dashboard_indicator\ParamConverter
 */
class DashboardIndicatorParamConverter implements ParamConverterInterface {

  /**
   * Entity Type Manager Service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * DashboardIndicatorParamConverter constructor.
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
    $storage = $this->entityTypeManager->getStorage('dashboard_indicator');
    $projects = $storage->loadByProperties(['key_unique' => $value]);
    if (!empty($projects)) {
      return reset($projects);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function applies($definition, $name, Route $route) {
    return (!empty($definition['type']) && $definition['type'] == 'dashboard:indicator');
  }

}
