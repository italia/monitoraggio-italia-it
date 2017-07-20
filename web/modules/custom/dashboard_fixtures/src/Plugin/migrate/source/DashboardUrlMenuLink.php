<?php

namespace Drupal\dashboard_fixtures\Plugin\migrate\source;

use Drupal\migrate_plus\Plugin\migrate\source\Url;
use Drupal\migrate\Row;

/**
 * Menu link base migration.
 *
 * Source:
 *   plugin: dashboardurlmenulink.
 *
 * @MigrateSource(
 *   id = "dashboardurlmenulink"
 * )
 */
class DashboardUrlMenuLink extends Url {

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
