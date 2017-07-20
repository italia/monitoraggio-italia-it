<?php

namespace Drupal\dashboard_indicator\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;

/**
 * Class ParagraphTargetEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "paragraph_target",
 *   name = @Translation("Rest Entity Decorator - Paragraph target"),
 *   entityType = "paragraph",
 *   bundle = "target"
 * )
 */
class ParagraphTargetEntityDecorator extends RestEntityDecoratorBase {

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    $year = $this->getDecoratedReferencedEntities('field_target_year', 0);
    return [
      'year' => $year['year'],
      'value' => $this->get('field_target_values'),
      'label' => $this->get('field_target_label'),
      'description' => $this->get('field_target_description'),
    ];
  }

}
