<?php

declare(strict_types=1);

namespace Drupal\ktpersonal\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a ktpersonal form.
 */
final class KtpersonalBuildForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ktpersonal_ktpersonal_build';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $request = \Drupal::request();

    $ktuser_inputs = $request->query->get('account');

    if (!empty($ktuser_inputs)) {

      /** @var \Drupal\ktpersonal\Entity\KtAccount[] $entities_kt_account */
      if ($entities_kt_account = \Drupal::entityTypeManager()
        ->getStorage('ktpersonal_kt_account')
        ->loadByProperties([
          'account_number' => $ktuser_inputs,
        ])) {
        $entities_kt_account = reset($entities_kt_account);
        $drupal_id_account = $entities_kt_account->id();

        $entities_kt_counters = \Drupal::entityTypeManager()
          ->getStorage('ktpersonal_kt_counter')
          ->loadByProperties([
            'kt_accounts_drupal_id' => $drupal_id_account,
          ]);

        $entities_kt_calculation = \Drupal::entityTypeManager()
          ->getStorage('ktpersonal_kt_calculation')
          ->loadByProperties([
            'kt_owner' => $drupal_id_account,
          ]);

        $last_array_of_entity_kt_calculation = end($entities_kt_calculation);

        $debt_field = $last_array_of_entity_kt_calculation->get('debt');
        $entity_array_debt = $debt_field->getValue();

        $apartment_array_field = $entities_kt_account->get('apartment_number')->getValue();
      }
    }

    $view_builder = \Drupal::entityTypeManager()->getViewBuilder('ktpersonal_kt_account');
    $output = $view_builder->view($entities_kt_account, 'short_ktaccount_info');

    $form['display_entity'] = $output;

    $form['view_account_info'] = [
      '#type' => 'view',
      '#name' => 'ktpersonal_ktaccount_info',
      '#display_id' => 'kt_personal_account_info_block',
      '#arguments' => [$ktuser_inputs],
    ];

    $form['view_debt'] = [
      '#type' => 'view',
      '#name' => 'ktpersonal_ktcalculation_debt',
      '#display_id' => 'debt_block',
      '#arguments' => [$ktuser_inputs],
    ];

    $form['view_counter'] = [
      '#type' => 'view',
      '#name' => 'ktpersonal_ktcounter',
      '#display_id' => 'kt_personal_counter_block',
      '#arguments' => [$ktuser_inputs],
    ];

    $form['counter_info'] = [
      '#type' => 'container',
      '#tree' => TRUE,
      '#prefix' => '<div id="counter_info-container">',
      '#suffix' => '</div>',
    ];

    foreach ($entities_kt_counters as $counter) {
      $last_info_array = $counter->get('id')->getValue();

      $last_info = $last_info_array[0]['value'];


      $form['counter_info']["$last_info"] = [
        '#type' => 'textfield',
        '#title' => '',
      ];
    }

    $form_rebulit_values = $form_state->getValues();
    $new_counters_info_array = $form_rebulit_values['counter_info'];

    if ($form_rebulit_values) {
      foreach ($new_counters_info_array as $new_counter_info) {
        $form['counter_new_info']["$new_counter_info"] = [
          '#type' => 'item',
          '#title' => "$new_counter_info",
        ];
      }
    }


    $form['change_account'] = [
      '#type' => 'submit',
      '#value' => $this->t('Змінити рахунок'),
    ];

    $form['submit_values'] = [
      '#type' => 'submit',
      '#value' => $this->t('Відправити показники'),
    ];

    // $form['change'] = [
    //      'change_account' => [
    //        '#type' => 'submit',
    //        '#value' => $this->t('Змінити рахунок'),
    //      ],
    //    ];
    //
    //    $form['actions'] = [
    //      'submit_values' => [
    //        '#type' => 'submit',
    //        '#value' => $this->t('Відправити показники'),
    //      ],
    //    ];
    $form['view_calculation'] = [
      '#type' => 'view',
      '#name' => 'ktpersonal_ktcalculation',
      '#display_id' => 'kt_personal_calculation_block',
      '#arguments' => [$ktuser_inputs],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    // @todo Validate the form here.
    // Example:
    // @code
    //   if (mb_strlen($form_state->getValue('message')) < 10) {
    //     $form_state->setErrorByName(
    //       'message',
    //       $this->t('Message should be at least 10 characters.'),
    //     );
    //   }
    // @endcode
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // $this->messenger()->addStatus($this->t('The message has been sent.'));
    $triggering_element = $form_state->getTriggeringElement();

    if ($triggering_element['#id'] == 'edit-change-account') {
      $form_state->setRedirect('ktpersonal.ktpersonal');
    }

    if ($triggering_element['#id'] == 'edit-submit-values') {
      $form_state->setRebuild();
    }


  }

}
