<?php

namespace Drupal\dashboard_base\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;

/**
 * Class DashboardDefaultEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "default_entity_decorator",
 *   name = @Translation("Rest Entity Decorator - Default"),
 *   entityType = "entity",
 *   bundle = NULL
 * )
 */
class DashboardDefaultEntityDecorator extends RestEntityDecoratorBase {

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return $this->values;
  }

}
