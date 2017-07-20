<?php

namespace Drupal\dashboard_fixtures\Plugin\migrate\process;

use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Row;

/**
 * This plugin replace dump values for debugging.
 *
 * @MigrateProcessPlugin(
 *   id = "custom_point"
 * )
 */
class CustomPoint extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    $lat = $value[0];
    $lon = $value[1];
    return 'POINT (' . $lat . ' ' . $lon . ')';
  }

}
