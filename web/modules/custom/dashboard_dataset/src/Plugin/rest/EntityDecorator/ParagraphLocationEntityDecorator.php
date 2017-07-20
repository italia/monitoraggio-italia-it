<?php

namespace Drupal\dashboard_dataset\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;

/**
 * Class ParagraphLocationEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "paragraph_location",
 *   name = @Translation("Rest Entity Decorator - Paragraph Location"),
 *   entityType = "paragraph",
 *   bundle = "location"
 * )
 */
class ParagraphLocationEntityDecorator extends RestEntityDecoratorBase {

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    $coordinates = $this->get('field_location_coordinates');
    return [
      'lat' => $coordinates['lat'],
      'lon' => $coordinates['lon'],
      'name' => $this->get('field_location_name'),
    ];
  }

}
