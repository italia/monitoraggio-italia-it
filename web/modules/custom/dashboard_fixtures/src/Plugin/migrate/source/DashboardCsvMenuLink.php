<?php

namespace Drupal\dashboard_fixtures\Plugin\migrate\source;

use Drupal\migrate\Row;

/**
 * Menu link base migration.
 *
 * source:
 *   plugin: dashboardcsvmenulink.
 *
 * @MigrateSource(
 *   id = "dashboardcsvmenulink"
 * )
 */
class DashboardCsvMenuLink extends Dashboardcsv {

  /**
   * Migration source file.
   */

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {

    $options = [];

    $row->setDestinationProperty('options', $options);
    $row->setSourceProperty('options', $options);
    return parent::prepareRow($row);
  }

}
