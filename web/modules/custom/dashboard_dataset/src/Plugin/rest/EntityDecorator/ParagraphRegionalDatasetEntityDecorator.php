<?php

namespace Drupal\dashboard_dataset\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;

/**
 * Class ParagraphRegionalDatasetEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "paragraph_regional_dataset",
 *   name = @Translation("Rest Entity Decorator - Paragraph Regional Dataset"),
 *   entityType = "paragraph",
 *   bundle = "regional_dataset"
 * )
 */
class ParagraphRegionalDatasetEntityDecorator extends RestEntityDecoratorBase {

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return [
      [
        'data' => $this->getDecoratedReferencedEntities('field_regional_values'),
      ],
    ];
  }

}
