<?php

namespace Drupal\ubg_apoxml\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ApoXML.
 */
class ApoXML extends ConfigFormBase
{

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return [
      'ubg_apoxml.apoxml',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'apo_xml_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('ubg_apoxml.apoxml');
    $form['decimal_separator'] = [
      '#type' => 'select',
      '#title' => $this->t('Decimal- Separator'),
      '#description' => $this->t('Dezimaltrennzeichen für alle Gleitkommawerte.'),
      '#options' => ['.' => $this->t('Punkt'), ',' => $this->t('Komma')],
      '#size' => 1,
      '#default_value' => $config->get('decimal_separator'),
    ];
    $form['unit'] = [
      '#type' => 'select',
      '#title' => $this->t('Unit'),
      '#description' => $this->t(''),
      '#options' => ['inch' => $this->t('inch'), 'mm' => $this->t('mm'), 'pts' => $this->t('pts')],
      '#size' => 1,
      '#default_value' => $config->get('unit'),
    ];

    $form['apoxml_ns'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ApoXML Namespace'),
      '#description' => $this->t(''),
      '#size' => 60,
      '#default_value' => $config->get('apoxml_ns'),
    ];

    $form['apoxml_agent_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Agent Name'),
      '#description' => $this->t(''),
      '#size' => 60,
      '#default_value' => $config->get('apoxml_agent_name'),
    ];

    $form['apoxml_agent_version'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Agent Version'),
      '#description' => $this->t(''),
      '#size' => 60,
      '#default_value' => $config->get('apoxml_agent_version'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    parent::submitForm($form, $form_state);

    $this->config('ubg_apoxml.apoxml')
      ->set('decimal_separator', $form_state->getValue('decimal_separator'))
      ->set('unit', $form_state->getValue('unit'))
      ->set('apoxml_ns', $form_state->getValue('apoxml_ns'))
      ->set('apoxml_agent_name', $form_state->getValue('apoxml_agent_name'))
      ->set('apoxml_agent_version', $form_state->getValue('apoxml_agent_version'))
      ->save();
  }
}
