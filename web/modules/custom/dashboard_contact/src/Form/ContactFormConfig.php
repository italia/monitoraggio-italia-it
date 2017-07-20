<?php

namespace Drupal\dashboard_contact\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ContactFormConfig.
 *
 * @package Drupal\dashboard_contact\Form
 */
class ContactFormConfig extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'dashboard_contact.contactformconfig',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dashboard_contact_conf';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dashboard_contact.contactformconfig');

    $tokens = '@name, @email, @select_value, @message';

    $form['dash_contact_intro'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Text before form'),
      '#description' => $this->t('Insert here the text before the form.'),
      '#default_value' => $config->get('dashboard_contact.contact_intro'),
    ];
    $form['dash_contact_select_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Type of contact form'),
      '#options' => [
        '1' => $this->t('Do not show Interest area select'),
        '2' => $this->t('Show Interest area select'),
      ],
      '#description' => $this->t('Use this option to select whether to show the select <em>Interest area</em>.'),
      '#default_value' => $config->get('dashboard_contact.contact_select_type'),
    ];
    $form['dash_contact_select_description'] = [
      '#markup' => $this->t('<em> Option <strong>Do not show interest area select</strong></em>: contact form does not will show select <em>Interest area</em> and system will send e-mail only to <em>Recipe</em>;<br /><em> Option <strong>Show Interest area select</strong></em>: contact form will show select <em>Interest area</em> and system will send e-mail to <em>Recipe</em> and selected <em>Interest area</em> element;<br /><strong>BE CAREFULL: token <em>@select_value</em> will be used only with option <em>Show Interest area select</em>. Check <em>Subject</em> and <em>E-mail text</em>.</strong>'),
    ];
    $form['dash_contact_subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#description' => $this->t("Use this field to set e-mail's Subject, available tokens from contact form: @tokens", ['@tokens' => $tokens]),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('dashboard_contact.contact_subject'),
    ];
    $form['dash_contact_text'] = [
      '#type' => 'textarea',
      '#title' => $this->t('E-mail text'),
      '#description' => $this->t('Insert here the body text, available tokens from contact form: @tokens', ['@tokens' => $tokens]),
      '#default_value' => $config->get('dashboard_contact.contact_text'),
    ];
    $form['dash_contact_recipient'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Recipient'),
      '#description' => $this->t('Insert default e-mail recipes.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('dashboard_contact.contact_recipient'),
    ];
    $form['dash_contact_interest_mail'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Interest area'),
      '#description' => $this->t('Insert here elements to show inside the select <em>Area di interesse</em>. Every element in a line. <br /> Exemple: <em>info@mail.loc|Label</em>'),
      '#default_value' => $config->get('dashboard_contact.contact_interest_mail'),
    ];
    $form['dash_contact_message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
      '#description' => $this->t('The message to display to the user after submission of this form. Leave blank for no message.'),
      '#default_value' => $config->get('dashboard_contact.contact_message'),
    ];
    $form['dash_contact_message_error'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Error Message'),
      '#description' => $this->t('The error message to display to the user after submission of this form. Leave blank for no message.'),
      '#default_value' => $config->get('dashboard_contact.contact_message_error'),
    ];
    $form['dash_contact_consent'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Text consent data'),
      '#description' => $this->t('Insert text for checkbox "Acconsento".'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('dashboard_contact.contact_consent'),
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

    $this->config('dashboard_contact.contactformconfig')
      ->set('dashboard_contact.contact_intro', $form_state->getValue('dash_contact_intro'))
      ->set('dashboard_contact.contact_select_type', $form_state->getValue('dash_contact_select_type'))
      ->set('dashboard_contact.contact_subject', $form_state->getValue('dash_contact_subject'))
      ->set('dashboard_contact.contact_text', $form_state->getValue('dash_contact_text'))
      ->set('dashboard_contact.contact_recipient', $form_state->getValue('dash_contact_recipient'))
      ->set('dashboard_contact.contact_interest_mail', $form_state->getValue('dash_contact_interest_mail'))
      ->set('dashboard_contact.contact_message', $form_state->getValue('dash_contact_message'))
      ->set('dashboard_contact.contact_message_error', $form_state->getValue('dash_contact_message_error'))
      ->set('dashboard_contact.contact_consent', $form_state->getValue('dash_contact_consent'))
      ->save();
  }

}
