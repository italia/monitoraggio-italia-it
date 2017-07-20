<?php

namespace Drupal\dashboard_dataset;

/**
 * Class DashboardDatasetTableBuilder.
 *
 * @package Drupal\dashboard_dataset
 */
class DashboardDatasetTableBuilder implements DashboardDatasetTableBuilderInterface {

  /**
   * {@inheritdoc}
   */
  public function build(array $dataset) {
    $header = [];
    $rows = [];

    foreach ($dataset['columns'] as $column) {
      if (is_array($column['label']) && isset($column['label']['value'])) {
        $header[] = $column['label']['value'];
      }
      else {
        $header[] = $column['label'];
      }
    }

    $check_name = FALSE;
    $is_lat_long_dataset = FALSE;

    // Cycle on all series to print rows.
    foreach ($dataset['series'] as $serie) {
      if (empty($serie['data'])) {
        continue;
      }

      if (!$check_name && !empty($serie['name'])) {
        array_unshift($header, '');
        $check_name = TRUE;
      }

      // If the value is a percentage value, the data is an array and we have
      // a ['percentage'] equal to TRUE key.
      foreach ($serie['data'] as $idx => $data) {
        if (isset($serie['data'][$idx]['percentage']) && $serie['data'][$idx]['percentage']) {
          $serie['data'][$idx] = $serie['data'][$idx]['value'] . ' %';
        };
      }

      $row = $serie['data'];

      // When $serie['data'] is an array of arrays we cannot map the row to
      // the $serie['data']; we need some more logic.
      // Dataset with regions maps case.
      if (isset($serie['data'][0]['value'])) {
        // Resetting the row array.
        $row = [];
        foreach ($serie['data'] as $data) {
          if (isset($data['value']['percentage'])) {
            $row[] = $data['value']['value'] . ' %';
          }
          else {
            $row[] = $data['value'];
          }
        }
      }

      // Dataset with latitude and longitude values.
      $is_lat_long_dataset = (isset($serie['data'][0]['lat']) &&
        isset($serie['data'][0]['lon']) &&
        isset($serie['data'][0]['name']));
      if ($is_lat_long_dataset) {
        // Resetting the row array.
        $row = [];
        foreach ($serie['data'] as $data) {
          $row[] = [
            $data['lat'],
            $data['lon'],
            $data['name'],
          ];
        }
      }

      // Remove the serie name from the row.
      if (!empty($serie['name'])) {
        array_unshift($row, $serie['name']);
      }
      // Add current row to the rows array.
      $rows[] = $row;
    }

    // The dataset array with lat and lon values has an additional nesting
    // level, so the only one row contain all rows.
    // Note that the "Pointer on Map" Dataset can have only one series.
    if ($is_lat_long_dataset) {
      $rows = $rows[0];
    }

    return [
      'header' => $header,
      'rows' => $rows,
    ];
  }

}
