<?php

namespace Drupal\dashboard_fixtures\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * This plugin replace elements in strings.
 *
 * @MigrateProcessPlugin(
 *   id = "decimal_percentage"
 * )
 */
class DecimalPercentage extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $value = trim($value);
    $delimiter = '|';
    $is_percentage = 0;

    if (isset($this->configuration['delimiter'])) {
      $delimiter = $this->configuration['delimiter'];
    }
    // Explode based on delimiter.
    list($field_value, $is_percentage) = explode($delimiter, $value, 2);

    if (!preg_match('/0|1/', $is_percentage)) {
      $is_percentage = 0;
    }

    return [
      'value' => $field_value,
      'percentage' => $is_percentage,
    ];

  }

}
