<?php

namespace Drupal\dashboard_base\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\DecimalFormatter;

/**
 * Plugin implementation of the 'decimal_percentage_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "decimal_percentage_field_formatter",
 *   label = @Translation("Decimal percentage field formatter"),
 *   field_types = {
 *     "decimal_percentage_field_type"
 *   }
 * )
 */
class DecimalPercentageFieldFormatter extends DecimalFormatter {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    foreach ($items as $delta => $item) {
      $values = $item->getValue();
      if ($values['percentage']) {
        $elements[$delta]['#markup'] .= ' %';
      }
      $elements[$delta]['#markup'] = str_replace(',00', '', $elements[$delta]['#markup']);
    }

    return $elements;
  }

}
