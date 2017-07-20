<?php

namespace Drupal\dashboard_dataset\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;

/**
 * Class ParagraphNameValuesYearEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "paragraph_name_values_year",
 *   name = @Translation("Rest Entity Decorator - Paragraph Name Values Year"),
 *   entityType = "paragraph",
 *   bundle = "name_values_year"
 * )
 */
class ParagraphNameValuesYearEntityDecorator extends RestEntityDecoratorBase {

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return [
      'name' => $this->get('field_name_values_year_name'),
      'data' => $this->get('field_name_values_year_value'),
    ] + $this->getDecoratedReferencedEntities('field_name_values_year_year', 0);
  }

}
