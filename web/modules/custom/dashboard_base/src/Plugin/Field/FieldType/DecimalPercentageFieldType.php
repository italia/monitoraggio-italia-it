<?php

namespace Drupal\dashboard_base\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Field\Plugin\Field\FieldType\DecimalItem;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'decimal_percentage_field_type' field type.
 *
 * @FieldType(
 *   id = "decimal_percentage_field_type",
 *   label = @Translation("Decimal percentage field type"),
 *   description = @Translation("My Field Type"),
 *   category = @Translation("Number"),
 *   default_widget = "decimal_percentage_field_widget",
 *   default_formatter = "decimal_percentage_field_formatter"
 * )
 */
class DecimalPercentageFieldType extends DecimalItem {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
      'precision' => 14,
      'scale' => 2,
    ] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(t('Decimal value'))
      ->setRequired(TRUE);

    $properties['percentage'] = DataDefinition::create('boolean')
      ->setLabel(t('Is percentage'))
      ->setRequired(FALSE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type' => 'numeric',
          'precision' => $field_definition->getSetting('precision'),
          'scale' => $field_definition->getSetting('scale'),
        ],
        'percentage' => [
          'type' => 'int',
          'size' => 'tiny',
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    $values = parent::getValue();
    $values['value'] = (float) $values['value'];
    if (isset($values['percentage'])) {
      $values['percentage'] = (bool) $values['percentage'];
    }
    return $values;
  }

}
