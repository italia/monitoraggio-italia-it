<?php

namespace Drupal\dashboard_dataset\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;
use Drupal\dashboard_base\DecimalPercentageTrait;

/**
 * Class ParagraphRegionalValuesEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "paragraph_regional_values",
 *   name = @Translation("Rest Entity Decorator - Paragraph Regional Values"),
 *   entityType = "paragraph",
 *   bundle = "regional_values"
 * )
 */
class ParagraphRegionalValuesEntityDecorator extends RestEntityDecoratorBase {

  use DecimalPercentageTrait;

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    $value = $this->get('field_decimal_value');
    $regions = $this->getEntity()
      ->get('field_region')
      ->getDataDefinition()
      ->getSetting('allowed_values');
    $region = $this->get('field_region', 0);

    return [
      'name' => $regions[$region],
      'value' => $this->getDecimalPercentageValue($value),
      'color' => $this->get('field_color', 0),
    ];
  }

}
