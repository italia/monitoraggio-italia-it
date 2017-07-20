<?php

namespace Drupal\dashboard_widget\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Dashboard widget edit forms.
 *
 * @ingroup dashboard_widget
 */
class DashboardWidgetForm extends ContentEntityForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dashboard_widget\Entity\DashboardWidget */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Dashboard widget.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Dashboard widget.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.dashboard_widget.canonical', ['dashboard_widget' => $entity->id()]);
  }

}
