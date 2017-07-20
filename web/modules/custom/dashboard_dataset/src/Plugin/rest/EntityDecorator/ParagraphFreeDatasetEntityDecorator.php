<?php

namespace Drupal\dashboard_dataset\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;

/**
 * Class ParagraphFreeDatasetEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "paragraph_free_dataset",
 *   name = @Translation("Rest Entity Decorator - Paragraph Free Dataset"),
 *   entityType = "paragraph",
 *   bundle = "free_dataset"
 * )
 */
class ParagraphFreeDatasetEntityDecorator extends RestEntityDecoratorBase {

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return [
      [
        'name' => '',
        'data' => $this->getDecoratedReferencedEntities('field_free_values'),
      ],
    ];
  }

}
