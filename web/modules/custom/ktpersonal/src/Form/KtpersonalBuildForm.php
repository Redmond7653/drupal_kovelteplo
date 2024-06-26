<?php

declare(strict_types=1);

namespace Drupal\ktpersonal\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ktpersonal\Entity\CounterLog;

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
        '#type' => 'number',
        '#title' => '',
      ];
    }

    $form_state_values = $form_state->getValues();

    if ($form_state_values) {

      /** @var \Drupal\ktpersonal\Entity\KtCounter[] $entities_kt_counters */

      foreach ($form_state_values['counter_info'] as $key => $new_counter_value) {



        $ts = strtotime(date('Y-m-d'));


        $query = \Drupal::entityTypeManager()
          ->getStorage('ktpersonal_counterlog')->getQuery();

        // $query = \Drupal::entityQuery('ktpersonal_counterlog');
        $query->condition('created', $ts, '>=');
        $query->condition('created', $ts + 24 * 60 * 60, '<');
        $query->condition('last_data', $new_counter_value, '=');

        $query->accessCheck(FALSE);

        $result = $query->execute();

        $counter_info = $entities_kt_counters[$key]->get('info')->getValue();



        if (empty($result)) {
          $counter_log = CounterLog::create([
            'info' => $counter_info[0]['value'],

            'last_data' => "$new_counter_value",
          ]);
          $counter_log->save();
        }

        // $query->condition('field_date', $now->format(DateTimeItemInterface::DATETIME_STORAGE_FORMAT), '>=');
        //        $counter_log = CounterLog::create([
        //          'info' => $counter_info[0]['value'],
        //          'apartment_number' => $apparment_number[0]['value'],
        //          'owner_account_number' => $account_owner[0]['value'],
        //          'last_data' => "$new_counter_value",
        //        ]);
        //
        //        $counter_log->save();
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
      // $form_state->setRedirect('ktpersonal.ktpersonal_edit');
    }

  }

}
