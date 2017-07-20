<?php

namespace Drupal\dashboard_dataset\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;

/**
 * Class ParagraphMonthlyDatasetEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "paragraph_monthly_dataset",
 *   name = @Translation("Rest Entity Decorator - Paragraph Monthly Dataset"),
 *   entityType = "paragraph",
 *   bundle = "monthly_dataset"
 * )
 */
class ParagraphMonthlyDatasetEntityDecorator extends RestEntityDecoratorBase {

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return $this->getDecoratedReferencedEntities('field_monthly_values');
  }

}
