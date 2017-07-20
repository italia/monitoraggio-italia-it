<?php

namespace Drupal\dashboard_project\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DashboardProjectTypeForm.
 *
 * @package Drupal\dashboard_project\Form
 */
class DashboardProjectTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $project_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $project_type->label(),
      '#description' => $this->t("Label for the Project type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $project_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\dashboard_project\Entity\DashboardProjectType::load',
      ],
      '#disabled' => !$project_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $project_type = $this->entity;
    $status = $project_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Project type.', [
          '%label' => $project_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Project type.', [
          '%label' => $project_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($project_type->urlInfo('collection'));
  }

}
