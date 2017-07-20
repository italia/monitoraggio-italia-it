<?php

namespace Drupal\dashboard_base\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\NumberWidget;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'decimal_percentage_field_widget' widget.
 *
 * @FieldWidget(
 *   id = "decimal_percentage_field_widget",
 *   label = @Translation("Decimal percentage field widget"),
 *   field_types = {
 *     "decimal_percentage_field_type"
 *   }
 * )
 */
class DecimalPercentageFieldWidget extends NumberWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    $element['value']['#weight'] = 0;
    $element['value']['#step'] = 'any';

    $element['percentage'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Is percentage'),
      '#default_value' => isset($items[$delta]->percentage) ? $items[$delta]->percentage : NULL,
      '#weight' => 12,
    ];

    return $element;
  }

}
