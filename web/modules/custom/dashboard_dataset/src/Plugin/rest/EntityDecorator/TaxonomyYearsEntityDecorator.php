<?php

namespace Drupal\dashboard_dataset\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;

/**
 * Class TaxonomyYearsEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "taxonomy_year",
 *   name = @Translation("Rest Entity Decorator - Taxonomy Years"),
 *   entityType = "taxonomy_term",
 *   bundle = "years"
 * )
 */
class TaxonomyYearsEntityDecorator extends RestEntityDecoratorBase {

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return [
      'year' => (int) $this->get('name'),
    ];
  }

}
