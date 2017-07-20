<?php

namespace Drupal\dashboard_indicator\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DashboardIndicatorTypeForm.
 *
 * @package Drupal\dashboard_indicator\Form
 */
class DashboardIndicatorTypeForm extends EntityForm {
  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $dashboard_indicator_type = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $dashboard_indicator_type->label(),
      '#description' => $this->t("Label for the Dashboard indicator type."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $dashboard_indicator_type->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\dashboard_indicator\Entity\DashboardIndicatorType::load',
      ),
      '#disabled' => !$dashboard_indicator_type->isNew(),
    );

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $dashboard_indicator_type = $this->entity;
    $status = $dashboard_indicator_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Dashboard indicator type.', [
          '%label' => $dashboard_indicator_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Dashboard indicator type.', [
          '%label' => $dashboard_indicator_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($dashboard_indicator_type->urlInfo('collection'));
  }

}
