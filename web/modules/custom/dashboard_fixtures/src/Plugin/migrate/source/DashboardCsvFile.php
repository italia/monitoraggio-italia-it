<?php

namespace Drupal\dashboard_fixtures\Plugin\migrate\source;

use Drupal\migrate\Row;

/**
 * File base migration.
 * This is the exact id used in yml file to name the plugin.
 * In this case
 * source:
 *   plugin: dashboardcsvfile.
 *
 * @MigrateSource(
 *   id = "dashboardcsvfile"
 * )
 */
class DashboardCsvFile extends Dashboardcsv {

  /**
   * Migration source file.
   */

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $filename = trim($row->getSourceProperty('filename'));
    $filename = str_replace(' ', '_', $filename);
    $folder = $row->getSourceProperty('destination_folder_name');
    $remote_folder_name = $row->getSourceProperty('remote_folder_name');

    $path = 'modules/custom/dashboard_fixtures/assets/' . $filename;
    $remote_path = $remote_folder_name . '/' . $filename;
    $uri = 'public://' . $folder . '/' . $filename;

    $row->setSourceProperty('uri', $uri);
    $row->setDestinationProperty('uri', $uri);
    $row->setSourceProperty('path', $path);
    $row->setSourceProperty('remote_path', $remote_path);

    return parent::prepareRow($row);
  }

}
