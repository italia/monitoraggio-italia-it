<?php

namespace Drupal\dashboard_indicator\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Dashboard indicator edit forms.
 *
 * @ingroup dashboard_indicator
 */
class DashboardIndicatorForm extends ContentEntityForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\dashboard_indicator\Entity\DashboardIndicator */
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
        drupal_set_message($this->t('Created the %label Dashboard indicator.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Dashboard indicator.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.dashboard_indicator.canonical', ['dashboard_indicator' => $entity->id()]);
  }

}
