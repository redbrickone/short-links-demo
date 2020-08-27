<?php

namespace Drupal\shortlinks\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\Request;

class ShortenShortlinksForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'shortlinks_form_shorten';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
        $form_state_values = $form_state->getValues();
        $storage = &$form_state->getStorage();

        $form['shortlinks_base_url'] = [
            '#type' => 'textfield',
            '#title' => t('URL'),
            '#default_value' => '',
            '#required' => TRUE,
            '#size' => 25,
            '#maxlength' => 2048,
            '#attributes' => [
                'class' => [
                    'shortlinks_base_url',
                ],
            ],
        ];

        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
            '#button_type' => 'primary'
        ];
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        $values = $form_state->getValues();
        $storage = &$form_state->getStorage();

        $url = $values['shortlinks_base_url'];
        if (\Drupal\Component\Utility\Unicode::strlen($url) > 4) {
            if (!strpos($url, '.', 1)) {
                $form_state->setErrorByName('url', $this->t('Please Enter a valid URL'));
            }
        }
        else {
            $form_state->setErrorByName('url', $this->t('Please Enter a valid URL.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {

        $long_url = $form_state->getValue('shortlinks_base_url');
        $short_url = get_shortlink((string) $long_url);
        $short_code = basename($short_url);
        
        // Add short link info to the database
        $field_array = [
            'long_url' => $long_url,
            'short_url' => $short_url,
            'short_code' => $short_code,
        ];

        $query = \Drupal::database();
        $query->insert('short_urls')
            ->fields($field_array)
            ->execute();
    
        // Redirect to Short Link info page.
        $form_state->setRedirectUrl(Url::fromUserInput('/view/' . $short_code));
    }
}