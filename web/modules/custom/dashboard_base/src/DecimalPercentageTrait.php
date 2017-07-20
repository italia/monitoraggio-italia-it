<?php

namespace Drupal\dashboard_base;

/**
 * Class DecimalPercentageTrait.
 *
 * @package Drupal\dashboard_base
 */
trait DecimalPercentageTrait {

  /**
   * Get decimal percentage value.
   *
   * @param array $data
   *   An associative array containing value and percentage keys.
   *
   * @return float
   *   The decimal percentage value.
   */
  public function getDecimalPercentageValue(array $data) {
    $value = $data['value'];
    if ($data['value'] == -1) {
      return '';
    }
    if ($data['percentage']) {
      return $data;
    }
    return (float) $value;
  }

}
