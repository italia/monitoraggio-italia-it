<?php

namespace Drupal\dashboard_fixtures\Plugin\migrate\source;

use Drupal\migrate\Row;

/**
 * File base migration.
 * This is the exact id used in yml file to name the plugin.
 * In this case
 * source:
 *   plugin: dashboardcsvfilepath.
 *
 * @MigrateSource(
 *   id = "dashboardcsvfilepath"
 * )
 */
class DashboardCsvFilePath extends Dashboardcsv {

  /**
   * Migration source file.
   */

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $row_sources = $row->getSource();
    $filename = trim($row->getSourceProperty('filename'));
    $filename = str_replace(' ', '_', $filename);
    $given_folder = $row_sources['dest_dir'];

    $path = 'modules/custom/dashboard_fixtures/assets/' . $filename;
    $uri = 'public://' . $given_folder . '/' . $filename;

    $row->setSourceProperty('uri', $uri);
    $row->setDestinationProperty('uri', $uri);
    $row->setSourceProperty('path', $path);

    return parent::prepareRow($row);
  }

}
