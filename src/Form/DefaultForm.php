<?php

namespace Drupal\webform_content_access\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DefaultForm.
 */
class DefaultForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'webform_content_access.default',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'default_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('webform_content_access.default');
    $form['content_type_to_lock'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Content type to lock'),
      '#description' => $this->t('Select a content type to lock with webform.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('content_type_to_lock'),
    ];
    $form['fields_to_lock'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Fields to lock'),
      '#description' => $this->t('Insert the fields name to lock, divided by comma.'),
      '#maxlength' => 255,
      '#size' => 64,
      '#default_value' => $config->get('fields_to_lock'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('webform_content_access.default')
      ->set('content_type_to_lock', $form_state->getValue('content_type_to_lock'))
      ->set('fields_to_lock', $form_state->getValue('fields_to_lock'))
      ->save();
  }

}
