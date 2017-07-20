<?php

namespace Drupal\dashboard_indicator;

use Drupal\dashboard_indicator\Entity\DashboardIndicator;

/**
 * Interface DashboardIndicatorCsvManagerInterface.
 *
 * @package Drupal\dashboard_indicator
 */
interface DashboardIndicatorCsvManagerInterface {

  /**
   * Handles CSV.
   *
   * @param \Drupal\dashboard_indicator\Entity\DashboardIndicator $indicator
   *   A Dashboard Indicator entity.
   */
  public function handleCsv(DashboardIndicator $indicator);

  /**
   * Get files.
   *
   * @return array
   *   An array of files to be downloaded.
   */
  public function getFiles();

  /**
   * Get CSV directory URI.
   *
   * @param string $key
   *   The Indicator unique key.
   *
   * @return string
   *   The CSV directory URI.
   */
  public function getCsvDirectoryUri($key);

  /**
   * Get CSV file URI.
   *
   * @param string $indicator_key
   *   The Indicator unique key.
   * @param string $dataset_key
   *   The Dataset unique key.
   *
   * @return string
   *   The CSV file URI.
   */
  public function getCsvFileUri($indicator_key, $dataset_key);

  /**
   * Get Zip Archive directory.
   *
   * @param string $key
   *   The Indicator unique key.
   *
   * @return string|false
   *   The Zip Archive directory path.
   */
  public function getZipArchiveDirectory($key);

  /**
   * Get Zip Archive file path.
   *
   * @param string $key
   *   The Indicator unique key.
   *
   * @return string
   *   The Zip Archive file path.
   */
  public function getZipArchiveFilePath($key);

  /**
   * Get Zip Archive file URI.
   *
   * @param string $key
   *   The Indicator unique key.
   *
   * @return string
   *   The Zip Archive file URI.
   */
  public function getZipArchiveFileUri($key);

}
