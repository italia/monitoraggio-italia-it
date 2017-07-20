<?php

namespace Drupal\dashboard_indicator\Controller;

use Drupal\advanced_rest\AdvancedRestEntityDecoratorFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\dashboard_indicator\DashboardIndicatorCsvManagerInterface;
use Drupal\dashboard_indicator\Entity\DashboardIndicator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DashboardIndicatorCsvController.
 *
 * @package Drupal\dashboard_indicator\Controller
 */
class DashboardIndicatorCsvController extends ControllerBase {

  /**
   * The Rest Decorator factory.
   *
   * @var \Drupal\advanced_rest\AdvancedRestEntityDecoratorFactoryInterface
   */
  protected $decorator;

  /**
   * The Dashboard Indicator CSV Manager Service.
   *
   * @var \Drupal\dashboard_indicator\DashboardIndicatorCsvManagerInterface
   */
  protected $csvManager;

  /**
   * DashboardIndicatorCsvController constructor.
   *
   * @param \Drupal\advanced_rest\AdvancedRestEntityDecoratorFactoryInterface $decorator
   *   The Rest Decorator factory.
   * @param \Drupal\dashboard_indicator\DashboardIndicatorCsvManagerInterface $csv_manager
   *   The Dashboard Indicator CSV Manager Service.
   */
  public function __construct(AdvancedRestEntityDecoratorFactoryInterface $decorator, DashboardIndicatorCsvManagerInterface $csv_manager) {
    $this->decorator = $decorator;
    $this->csvManager = $csv_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static (
      $container->get('advanced_rest.entity_decorator_factory'),
      $container->get('dashboard_indicator.csv_manager')
    );
  }

  /**
   * Download CSV.
   *
   * @param \Drupal\dashboard_indicator\Entity\DashboardIndicator $indicator
   *   A Dashboard Indicator entity.
   *
   * @throws NotFoundHttpException
   */
  public function download(DashboardIndicator $indicator) {
    $data = $this->decorator->get($indicator)->toArray();
    $indicator_key = $data['key'];
    $dataset = $data['datasets'];
    if (empty($dataset)) {
      throw new NotFoundHttpException($this->t('There is no data for this indicator'));
    }

    $is_archive = count($dataset) > 1;
    $dataset_key = !$is_archive ? $dataset[0]['key'] : FALSE;

    if (!$is_archive) {
      $file_path = $this->csvManager->getZipArchiveDirectory($indicator_key);
      $file_path .= '/' . $dataset_key . '.csv';
    }
    else {
      $file_path = $this->csvManager->getZipArchiveFilePath($indicator_key);
    }

    if (!file_exists($file_path)) {
      $this->csvManager->handleCsv($indicator);
    }

    if (!$is_archive && $dataset_key) {
      $file_uri = $this->csvManager->getCsvFileUri($indicator_key, $dataset_key);
    }
    else {
      $file_uri = $this->csvManager->getZipArchiveFileUri($indicator_key);
    }

    $url = file_create_url($file_uri);
    $response = new RedirectResponse($url);
    $response->send();
  }

}
