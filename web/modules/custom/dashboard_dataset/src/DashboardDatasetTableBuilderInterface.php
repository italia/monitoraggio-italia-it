<?php

namespace Drupal\dashboard_dataset;

/**
 * Interface DashboardDatasetTableBuilderInterface.
 *
 * @package Drupal\dashboard_dataset
 */
interface DashboardDatasetTableBuilderInterface {

  /**
   * Transforms a Dataset into a table.
   *
   * @param array $dataset
   *   An array containing dataset values.
   *
   * @return array
   *   A table structure for the given dataset array.
   */
  public function build(array $dataset);

}
