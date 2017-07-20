<?php

namespace Drupal\dashboard_project\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DashDesiSettings.
 *
 * @package Drupal\dashboard_project\Form
 */
class DashDesiSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'dashboard_project.desisettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dash_desi_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dashboard_project.desisettings');

    // DESI settings Icon.
    $form['desi_link_intro'] = [
      '#markup' => $this->t('Insert here text data for <em>DESI</em> link.'),
      '#weight' => -10,
    ];
    $form['desi_link_label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Link label.'),
      '#description' => $this->t('Insert here link label.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('dash_desi_settings.desi_link_label'),
    ];
    $form['desi_link_path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Link path.'),
      '#description' => $this->t('Insert here link path.'),
      '#default_value' => $config->get('dash_desi_settings.desi_link_path'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    // Set DESI settings.
    $this->config('dashboard_project.desisettings')
      ->set('dash_desi_settings.desi_link_label', $form_state->getValue('desi_link_label'))
      ->set('dash_desi_settings.desi_link_path', $form_state->getValue('desi_link_path'))
      ->save();
  }

}
