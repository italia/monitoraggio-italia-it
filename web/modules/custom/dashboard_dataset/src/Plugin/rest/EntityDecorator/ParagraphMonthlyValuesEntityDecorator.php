<?php

namespace Drupal\dashboard_dataset\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;
use Drupal\dashboard_base\DecimalPercentageTrait;

/**
 * Class ParagraphMonthlyValuesEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "paragraph_monthly_values",
 *   name = @Translation("Rest Entity Decorator - Paragraph Monthly Values"),
 *   entityType = "paragraph",
 *   bundle = "monthly_values"
 * )
 */
class ParagraphMonthlyValuesEntityDecorator extends RestEntityDecoratorBase {

  use DecimalPercentageTrait;

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    $year = $this->getDecoratedReferencedEntities('field_year', 0)['year'];
    $group = $this->get('field_group');
    $name = !empty($group) ? $group : $year;
    $stack = !empty($group) ? $year : FALSE;

    $data = [
      'name' => $name,
      'data' => $this->getDataValues(),
      'stack' => $stack,
    ];

    return array_filter($data);
  }

  /**
   * Get data values.
   *
   * @return array
   *   An array containing monthly dataset values.
   */
  public function getDataValues() {
    $values = [];
    if (empty($this->get('field_monthly_value'))) {
      return $values;
    }
    foreach ($this->get('field_monthly_value') as $monthly_value) {
      $values[] = $this->getDecimalPercentageValue($monthly_value);
    }
    return $values;
  }

}
