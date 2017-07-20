<?php

namespace Drupal\dashboard_dataset\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\AdvancedRestEntityDecoratorFactoryInterface;
use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\paragraphs\ParagraphInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DatasetEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "dataset",
 *   name = @Translation("Rest Entity Decorator - Dataset"),
 *   entityType = "dataset",
 *   bundle = NULL
 * )
 */
class DatasetEntityDecorator extends RestEntityDecoratorBase {

  /**
   * The Date Formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
    AdvancedRestEntityDecoratorFactoryInterface $entity_decorator_factory,
    DateFormatterInterface $date_formatter) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $entity_type_manager, $entity_decorator_factory);

    $this->dateFormatter = $date_formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('advanced_rest.entity_decorator_factory'),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    $widgets = $this->getDecoratedReferencedEntities('field_dataset_st_widget');
    $series = $this->getDecoratedReferencedEntities('field_dataset_st_data', 0);
    $columns = $this->extractColumns();
    $data = [
      'key' => $this->get('key_unique'),
      'title' => $this->get('name'),
      'description' => $this->get('field_dataset_st_description', 'value'),
      'columns' => $columns,
      'series' => $series,
      'widget' => reset($widgets),
    ];

    return $data;
  }

  /**
   * Extract columns.
   *
   * @return array
   *   An array of columns extracted from the dataset.
   */
  public function extractColumns() {
    $series = $this->getEntity()
      ->get('field_dataset_st_data')
      ->referencedEntities();

    if (empty($series)) {
      return [];
    }

    $paragraph = reset($series);

    switch ($paragraph->getType()) {
      case 'free_dataset':
        $columns = $this->getFreeDatasetColumns($paragraph);
        break;

      case 'monthly_dataset':
        $columns = $this->getMonthlyDatasetColumns();
        break;

      case 'pointer_on_map_dataset':
        $columns = $this->getPointerOnMapColumns($paragraph);
        break;

      case 'regional_dataset':
        $columns = $this->getRegionalDatasetColumns($paragraph);
        break;

      default:
        $columns = [];
    }

    return $columns;
  }

  /**
   * Get Monthly Dataset columns.
   *
   * @return array
   *   An array of columns extracted from the dataset.
   */
  public function getMonthlyDatasetColumns() {
    $date = new \DateTime();
    $year = date('Y');
    $columns = [];

    for ($i = 1; $i <= 12; $i++) {
      $date->setDate($year, $i, 1);
      $timestamp = $date->getTimestamp();
      $label = $this->dateFormatter->format($timestamp, 'custom', 'F');
      $acronym = $this->dateFormatter->format($timestamp, 'custom', 'M');
      $columns[] = [
        'label' => $label,
        'acronym' => $acronym,
      ];
    }

    return $columns;
  }

  /**
   * Get Free Dataset columns.
   *
   * @param \Drupal\paragraphs\ParagraphInterface $paragraph
   *   A Paragraph entity.
   *
   * @return array
   *   An array of columns extracted from the dataset.
   */
  public function getFreeDatasetColumns(ParagraphInterface $paragraph) {
    $columns = [];
    $values = $paragraph->get('field_free_values')->referencedEntities();
    foreach ($values as $value) {
      $columns[] = [
        'label' => $value->get('field_key')->first()->value,
      ];
    }

    return $columns;
  }

  /**
   * Get Pointer on Map columns.
   *
   * @param \Drupal\paragraphs\ParagraphInterface $paragraph
   *   A Paragraph entity.
   *
   * @return array
   *   An array of columns extracted from the dataset.
   */
  public function getPointerOnMapColumns(ParagraphInterface $paragraph) {
    return [
      [
        'label' => 'Lat',
      ],
      [
        'label' => 'Lon',
      ],
      [
        'label' => $this->get('name'),
      ],
    ];
  }

  /**
   * Get Regional Dataset columns.
   *
   * @param \Drupal\paragraphs\ParagraphInterface $paragraph
   *   A Paragraph entity.
   *
   * @return array
   *   An array of columns extracted from the dataset.
   */
  public function getRegionalDatasetColumns(ParagraphInterface $paragraph) {
    $values = $paragraph->get('field_regional_values')->referencedEntities();
    $columns = [];
    $regions = [];

    foreach ($values as $value) {
      $field_region = $value->get('field_region');

      if (empty($regions)) {
        $regions = $field_region->getDataDefinition()
          ->getSetting('allowed_values');
      }

      $columns[] = [
        'label' => $regions[$field_region->first()->value],
      ];
    }

    return $columns;
  }

}
