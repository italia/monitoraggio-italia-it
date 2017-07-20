<?php

namespace Drupal\dashboard_fixtures\Plugin\migrate\source;

use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate_source_csv\CSVFileObject;
use Drupal\migrate_source_csv\Plugin\migrate\source\CSV;
use Drupal\migrate\Row;

/**
 * Foto base migration.
 * This is the exact id used in yml file to name the plugin.
 * In this case
 * source:
 *   plugin: dashboardcsv.
 *
 * @MigrateSource(
 *   id = "dashboardcsv"
 * )
 */
class Dashboardcsv extends CSV {

  /**
   * Migration source file.
   */

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {

    $mm_module_path = drupal_get_path('module', 'dashboard_fixtures');
    $configuration['path'] = $mm_module_path . '/data/' . $configuration['path'];

    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
  }

  /**
   * {@inheritdoc}
   *
   * Enable multi-line values in CSV files.
   */
  public function initializeIterator() {
    $file = parent::initializeIterator();
    // Exclude CSVFileObject::DROP_NEW_LINE flag to retain the first newline in
    // a multi-line value.
    $file->setFlags(CSVFileObject::READ_CSV | CSVFileObject::READ_AHEAD | CSVFileObject::SKIP_EMPTY);

    return $file;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {

    return parent::prepareRow($row);
  }

}
