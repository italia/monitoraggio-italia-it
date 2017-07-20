<?php

namespace Drupal\dashboard_dataset\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;

/**
 * Class ParagraphYearValuesEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "paragraph_year_values",
 *   name = @Translation("Rest Entity Decorator - Paragraph Year Values"),
 *   entityType = "paragraph",
 *   bundle = "year_values"
 * )
 */
class ParagraphYearValuesEntityDecorator extends RestEntityDecoratorBase {

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return [
      'data' => $this->get('field_year_values_value'),
    ] + $this->getDecoratedReferencedEntities('field_year_values_year', 0);
  }

}
