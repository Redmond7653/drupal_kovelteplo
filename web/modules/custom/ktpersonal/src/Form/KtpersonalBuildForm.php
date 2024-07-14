<?php

declare(strict_types=1);

namespace Drupal\ktpersonal\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ktpersonal\Entity\CounterLog;
use Drupal\views\Views;

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

    $pageRefreshed = isset($_SERVER['HTTP_CACHE_CONTROL']) &&($_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0' ||  $_SERVER['HTTP_CACHE_CONTROL'] == 'no-cache');
    if ($pageRefreshed == 1) {
      $z = 1;
    }
    else {
      // Enter code here.
      $z = 0;
    }

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

    // $form['view_counter'] = [
    //      '#type' => 'view',
    //      '#name' => 'ktpersonal_ktcounter',
    //      '#display_id' => 'kt_personal_counter_block',
    //      '#arguments' => [$ktuser_inputs],
    //    ];
    $view_test = Views::getView('ktpersonal_ktcounter');
    $view_test->setArguments([$ktuser_inputs]);
    $view_test->setDisplay('kt_personal_counter_block');
    $view_test->execute();

    $view_results = $view_test->result;

    /** @var \Drupal\views\ResultRow $view_result */
    // $counter_rows = [];
    //    foreach ($view_results as $view_result) {
    //      $counter_info = $view_result->_entity->get('info')->getValue();
    //      $counter_next_date = $view_result->_entity->get('next_date_checking')->getValue();
    //      $counter_last_data = $view_result->_entity->get('last_data')->getValue();
    //
    //      $last_info = $view_result->_entity->id();
    // $form['counter_info'] = [
    //        '#type' => 'container',
    //        '#prefix' => '<div id="counter_info-container">',
    //        '#suffix' => '</div>',
    //      ];
    //
    //      $key = 'ololo';
    //      $array[$key] = 'bebebe';
    //      $array = [
    //        $key => 'bebebe',
    //      ];
    //
    //      $input = [
    //        '#type' => 'number',
    //        //              '#title' => '',
    //        '#default_value' => '',
    //      ];
    //
    //      $container = [
    //        '#type' => 'container',
    //        'input---' . $view_result->_entity->id() => $input,
    //      ];
    //      $counter_rows['row-' . $view_result->_entity->id()] = [
    //        'counter' => [
    //          'data' => [
    //            '#markup' => $counter_info[0]['value'] . '<br>' . $counter_next_date[0]['value'],
    //          ],
    //        ],
    //
    //        'current_data' => $counter_last_data[0]['value'],
    //
    //        'ololo_input' => [
    //          'data' => [
    //            '#type' => 'number',
    //            '#name' => 'ololo-' . $view_result->_entity->id(),
    //            '#default_value' => '',
    //          ],
    //        ],
    //      ];
    //
    //    }
    //    $form['counter_table'] = [
    //      '#type' => 'table',
    //      '#tree' => TRUE,
    //      '#header' => [
    //        $this->t('Лічильник'),
    //        $this->t('Поточні показники'),
    //        $this->t('Нові показники'),
    //    // $this->t('Дата нових показників'),
    //      ],
    //      '#rows' => $counter_rows,
    //    ];
    // Foreach ($entities_kt_counters as $counter) {
    //
    //      $form['counter_info']["$last_info"] = [
    //        '#type' => 'number',
    //        '#title' => '',
    //      ];
    //    }.
    $form_state_values = $form_state->getValues();

    // If ($form_state_values['contacts']) {.
    $form['contacts'] = [
      '#type' => 'table',
      '#title' => 'Sample Table',
      '#header' => [
        $this->t('Лічильник'),
        $this->t('Поточні показники'),
        $this->t('Нові показники'),
        $this->t('Дата нових показників'),
      ],
    ];

//    foreach ($view_results as $view_result) {
//      $counter_info = $view_result->_entity->get('info')->getValue();
//      $form['contacts'][] = $counter_info;
//    }

    if ($form_state_values) {

      /** @var \Drupal\ktpersonal\Entity\KtCounter[] $entities_kt_counters */

      foreach ($form_state_values['contacts'] as $key => $new_counter_value) {

        $ts = strtotime(date('Y-m-d'));

        $counter_info = $entities_kt_counters[$key]->get('info')->getValue();

        $query = \Drupal::entityTypeManager()
          ->getStorage('ktpersonal_counterlog')->getQuery();

        // $query = \Drupal::entityQuery('ktpersonal_counterlog');
        $query->condition('created', $ts, '>=');
        $query->condition('created', $ts + 24 * 60 * 60, '<');
        $query->condition('last_data', $new_counter_value['new_data'], '=');
        $query->condition('info', $counter_info[0]['value'], '=');

        $query->accessCheck(FALSE);

        $result = $query->execute();

        $counter_info = $entities_kt_counters[$key]->get('id')->getValue();

        if (empty($result)) {
          $counter_log = CounterLog::create([
            'info' => $counter_info[0]['value'],

            'last_data' => $new_counter_value['new_data'],
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

    foreach ($view_results as $view_result) {

      $counter_info = $view_result->_entity->get('info')->getValue();
      $counter_next_date = $view_result->_entity->get('next_date_checking')->getValue();
      $counter_last_data = $view_result->_entity->get('last_data')->getValue();

      /** @var \Drupal\Core\Entity\Query\QueryInterface $query */
      $query = \Drupal::entityTypeManager()
        ->getStorage('ktpersonal_counterlog')->getQuery();

      $query->condition('info', $counter_info[0]['value'], '=');
//      $query->condition('created', strtotime(date('Y-m-d')), '>=');
//      $query->condition('created', strtotime(date('Y-m-d')) + 24 * 60 * 60 * 30, '<');
      $query->sort('created', 'DESC');
      $query->range(0,1);


      $query->accessCheck(FALSE);

      $result = $query->execute();

      $entities_kt_log = \Drupal::entityTypeManager()
        ->getStorage('ktpersonal_counterlog')
        ->load(reset($result)
        );

      if (!empty($entities_kt_log)) {


        $time_field = $entities_kt_log->get('created')
          ->getValue();

        $time_when_created = date('d.m.Y H:i', intval($time_field[0]['value']));
      }

      $form['contacts'][$view_result->_entity->id()]['counter'] = [
        '#markup' => $counter_info[0]['value'] . '<br>' . $counter_next_date[0]['value'],
      ];

      $form['contacts'][$view_result->_entity->id()]['current_data'] = [
        '#markup' => $counter_last_data[0]['value'],
      ];

      $form['contacts'][$view_result->_entity->id()]['new_data'] = [
        '#type' => 'number',
        '#title_display' => 'invisible',
      ];

      // If ($form_state_values['contacts']) {
      //        $current_date = date('d.m.Y');
      //        $current_time = date('H:i');.
      $form['contacts'][$view_result->_entity->id()]['new_date_of_counters'] = [
        '#markup' => $time_when_created,
      ];
      // }
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
    $z = 0;
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
      $check = TRUE;
    }

  }

}
