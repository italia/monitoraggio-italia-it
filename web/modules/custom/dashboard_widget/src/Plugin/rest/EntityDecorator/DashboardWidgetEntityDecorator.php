<?php

namespace Drupal\dashboard_widget\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;

/**
 * Class DashboardWidgetEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "dashboard_widget",
 *   name = @Translation("Rest Entity Decorator - Dashboard Widget"),
 *   entityType = "dashboard_widget",
 *   bundle = NULL
 * )
 */
class DashboardWidgetEntityDecorator extends RestEntityDecoratorBase {

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return [
      'type' => $this->get('field_highcharts_type'),
    ];
  }

}
