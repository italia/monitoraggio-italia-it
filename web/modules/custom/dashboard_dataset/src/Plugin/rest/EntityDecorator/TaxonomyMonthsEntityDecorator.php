<?php

namespace Drupal\dashboard_dataset\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;

/**
 * Class TaxonomyMonthsEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "taxonomy_months",
 *   name = @Translation("Rest Entity Decorator - Taxonomy Months"),
 *   entityType = "taxonomy_term",
 *   bundle = "months"
 * )
 */
class TaxonomyMonthsEntityDecorator extends RestEntityDecoratorBase {

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return [
      'label' => $this->get('name'),
      'acronym' => $this->get('field_tax_months_acronym'),
    ];
  }

}
