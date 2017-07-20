<?php

namespace Drupal\dashboard_dataset\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;
use Drupal\dashboard_base\DecimalPercentageTrait;

/**
 * Class ParagraphFreeValuesEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "paragraph_free_values",
 *   name = @Translation("Rest Entity Decorator - Paragraph Free Values"),
 *   entityType = "paragraph",
 *   bundle = "free_values"
 * )
 */
class ParagraphFreeValuesEntityDecorator extends RestEntityDecoratorBase {

  use DecimalPercentageTrait;

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    $value = $this->get('field_decimal_value', 0);
    return $this->getDecimalPercentageValue($value);
  }

}
