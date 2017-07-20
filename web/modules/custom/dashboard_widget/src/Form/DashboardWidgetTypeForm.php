<?php

namespace Drupal\dashboard_widget\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DashboardWidgetTypeForm.
 *
 * @package Drupal\dashboard_widget\Form
 */
class DashboardWidgetTypeForm extends EntityForm {
  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $dashboard_widget_type = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $dashboard_widget_type->label(),
      '#description' => $this->t("Label for the Dashboard widget type."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $dashboard_widget_type->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\dashboard_widget\Entity\DashboardWidgetType::load',
      ),
      '#disabled' => !$dashboard_widget_type->isNew(),
    );

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $dashboard_widget_type = $this->entity;
    $status = $dashboard_widget_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Dashboard widget type.', [
          '%label' => $dashboard_widget_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Dashboard widget type.', [
          '%label' => $dashboard_widget_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($dashboard_widget_type->urlInfo('collection'));
  }

}
