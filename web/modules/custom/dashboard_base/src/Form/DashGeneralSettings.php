<?php

namespace Drupal\dashboard_base\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DashGeneralSettings.
 *
 * @package Drupal\dashboard_base\Form
 */
class DashGeneralSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'dashboard_base.dashgeneralsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dash_general_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dashboard_base.dashgeneralsettings');

    // Custom block settings.
    $form['contact_bs_description_intro'] = [
      '#markup' => $this->t('Insert here text data for block <em>Contact</em> inside footer.'),
      '#weight' => -10,
    ];
    $form['contact_bs_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Block title'),
      '#description' => $this->t('Insert here block title.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('dash_base_block_contact.contact_bs_title'),
    ];
    $form['contact_bs_text'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Content'),
      '#description' => $this->t('Insert here block text.'),
      '#default_value' => $config->get('dash_base_block_contact.contact_bs_text'),
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

    $dash_blockarea = $form_state->getValue('contact_bs_text');
    $this->config('dashboard_base.dashgeneralsettings')
      // Set block custom settings.
      ->set('dash_base_block_contact.contact_bs_text', $dash_blockarea['value'])
      ->set('dash_base_block_contact.contact_bs_title', $form_state->getValue('contact_bs_title'))
      ->save();
  }

}
