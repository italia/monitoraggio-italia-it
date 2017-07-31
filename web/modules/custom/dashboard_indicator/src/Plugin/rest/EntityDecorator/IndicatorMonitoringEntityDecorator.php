<?php

namespace Drupal\dashboard_indicator\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;

/**
 * Class IndicatorMonitoringEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "indicator_monitoring",
 *   name = @Translation("Rest Entity Decorator - Indicator Monitoring"),
 *   entityType = "dashboard_indicator",
 *   bundle = "monitoring_indicator"
 * )
 */
class IndicatorMonitoringEntityDecorator extends RestEntityDecoratorBase {
  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return [
      'key' => $this->get('key_unique'),
      'name' => $this->get('name'),
      'update_frequency' => $this->get('field_indicator_update_frequency'),
      'last_update' => (int) $this->get('changed'),
      'description' => $this->get('field_indicator_description', 'value'),
      'value_title' => $this->get('field_indicator_value_title'),
      'value' => $this->get('field_indicator_value'),
      'value_description' => $this->get('field_indicator_value_descr'),
      'baseline2013' => $this->get('field_indicator_baseline_2013'),
      'targets' => $this->getDecoratedReferencedEntities('field_indicator_targets'),
      'datasets' => $this->getDecoratedReferencedEntities('field_indicator_dataset'),
    ];
  }

}
