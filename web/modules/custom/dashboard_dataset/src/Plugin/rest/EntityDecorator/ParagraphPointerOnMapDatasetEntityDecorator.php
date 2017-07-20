<?php

namespace Drupal\dashboard_dataset\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;

/**
 * Class ParagraphPointerOnMapDatasetEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "paragraph_pointer_on_map_dataset",
 *   name = @Translation("Rest Entity Decorator - Paragraph Pointer on Map Dataset"),
 *   entityType = "paragraph",
 *   bundle = "pointer_on_map_dataset"
 * )
 */
class ParagraphPointerOnMapDatasetEntityDecorator extends RestEntityDecoratorBase {

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return [
      [
        'data' => $this->getDecoratedReferencedEntities('field_location'),
      ],
    ];
  }

}
