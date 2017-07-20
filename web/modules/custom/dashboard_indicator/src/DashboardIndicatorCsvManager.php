<?php

namespace Drupal\dashboard_indicator;

use Drupal\advanced_rest\AdvancedRestEntityDecoratorFactoryInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\dashboard_dataset\DashboardDatasetTableBuilderInterface;
use Drupal\dashboard_indicator\Entity\DashboardIndicator;

/**
 * Class DashboardIndicatorCsvManager.
 *
 * @package Drupal\dashboard_indicator
 */
class DashboardIndicatorCsvManager implements DashboardIndicatorCsvManagerInterface {

  use StringTranslationTrait;

  /**
   * Definition of DASHBOARD_INDICATOR_CSV_DIR.
   */
  const DASHBOARD_INDICATOR_CSV_DIR = 'public://indicator/csv';

  /**
   * The Rest Decorator factory.
   *
   * @var \Drupal\advanced_rest\AdvancedRestEntityDecoratorFactoryInterface
   */
  protected $decorator;

  /**
   * The Dashboard Table Builder service.
   *
   * @var \Drupal\dashboard_dataset\DashboardDatasetTableBuilderInterface
   */
  protected $datasetTableBuilder;

  /**
   * File System service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  /**
   * An array of CSV files.
   *
   * @var array
   */
  protected $files;

  /**
   * DashboardIndicatorCsvController constructor.
   *
   * @param \Drupal\advanced_rest\AdvancedRestEntityDecoratorFactoryInterface $decorator
   *   The Rest Decorator factory.
   * @param \Drupal\dashboard_dataset\DashboardDatasetTableBuilderInterface $builder
   *   The Dashboard Table Builder service.
   * @param \Drupal\Core\File\FileSystemInterface $file_system
   *   A File System service instance.
   */
  public function __construct(AdvancedRestEntityDecoratorFactoryInterface $decorator, DashboardDatasetTableBuilderInterface $builder, FileSystemInterface $file_system) {
    $this->decorator = $decorator;
    $this->datasetTableBuilder = $builder;
    $this->fileSystem = $file_system;
    $this->files = [];
  }

  /**
   * {@inheritdoc}
   */
  public function getFiles() {
    return $this->files;
  }

  /**
   * {@inheritdoc}
   */
  public function handleCsv(DashboardIndicator $indicator) {
    $data = $this->decorator->get($indicator)->toArray();
    if (empty($data['datasets'])) {
      return;
    }

    $folder = $this->getZipArchiveDirectory($data['key']);
    if (empty($folder)) {
      throw new \RuntimeException($this->t('There was a problem while retrieving Indicators export dir.'));
    }

    // Delete existing files.
    foreach (glob("$folder/*") as $file) {
      unlink($file);
    }

    foreach ($data['datasets'] as $dataset) {
      $this->generateCsv($data['key'], $dataset);
    }

    if (count($this->getFiles()) > 1) {
      $this->generateCsvArchive($data);
    }
  }

  /**
   * Generate CSV.
   *
   * @param string $indicator_key
   *   The Indicator unique key.
   * @param array $dataset
   *   An array containing dataset data.
   */
  protected function generateCsv($indicator_key, array $dataset) {
    $dir = $this->getZipArchiveDirectory($indicator_key);
    $csv = [
      'filename' => $dir . '/' . $dataset['key'] . '.csv',
      'data' => NULL,
    ];
    $build = $this->datasetTableBuilder->build($dataset);

    if (!isset($build['header']) || !isset($build['rows'])) {
      $error = $this->t('Something went wrong while generating CSV for "@title"', [
        '@title' => $dataset['title'],
      ]);
      throw new \RuntimeException($error);
    }

    $csv['data'] = [$build['header']];
    foreach ($build['rows'] as $row) {
      $csv['data'][] = $row;
    }

    $fp = fopen($csv['filename'], 'w');
    foreach ($csv['data'] as $fields) {
      fputcsv($fp, $fields);
    }
    fclose($fp);

    $this->files[] = $csv;
  }

  /**
   * Generate CSV Archive.
   *
   * @param array $data
   *   An array containing data of a decorated Indicator entity.
   *
   * @return \ZipArchive
   *   The generated Zip Archive.
   */
  protected function generateCsvArchive(array $data) {
    $zip = new \ZipArchive();
    $filename = $this->getZipArchiveFilePath($data['key']);
    $zip->open($filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

    foreach ($this->getFiles() as $file) {
      $file_segments = explode('/', $file['filename']);
      $zip->addFile($file['filename'], end($file_segments));
    }
    $zip->close();

    return $zip;
  }

  /**
   * {@inheritdoc}
   */
  public function getCsvDirectoryUri($key) {
    return self::DASHBOARD_INDICATOR_CSV_DIR . '/' . $key;
  }

  /**
   * {@inheritdoc}
   */
  public function getCsvFileUri($indicator_key, $dataset_key) {
    $csv_dir = $this->getCsvDirectoryUri($indicator_key);
    return $csv_dir . '/' . $dataset_key . '.csv';
  }

  /**
   * {@inheritdoc}
   */
  public function getZipArchiveDirectory($key) {
    $csv_dir = $this->getCsvDirectoryUri($key);
    file_prepare_directory($csv_dir, FILE_CREATE_DIRECTORY);
    return $this->fileSystem->realpath($csv_dir);
  }

  /**
   * {@inheritdoc}
   */
  public function getZipArchiveFilePath($key) {
    $folder = $this->getZipArchiveDirectory($key);
    return "$folder/$key.zip";
  }

  /**
   * {@inheritdoc}
   */
  public function getZipArchiveFileUri($key) {
    $folder = $this->getCsvDirectoryUri($key);
    return "$folder/$key.zip";
  }

}
