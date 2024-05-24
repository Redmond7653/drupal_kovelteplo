<?php

declare(strict_types=1);

namespace Drupal\ktpersonal\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a ktpersonal form.
 */
final class KtpersonalForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ktpersonal_ktpersonal';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $ktuser_inputs = $form_state->getUserInput();

    if (!empty($ktuser_inputs)) {
      $entities_kt_counters = \Drupal::entityTypeManager()
        ->getStorage('kt_counter')
        ->loadByProperties([
          'personal_account_number_field' => $ktuser_inputs,
        ]);

      // todo: Зробити методи для загрузки entity: accounts, calculation, counters
      foreach ($entities_kt_counters as $entity) {
        $drupal_array_id_account = $entity->get('kt_accounts_drupal_id_field')
          ->getValue();
        $drupal_id_account = $drupal_array_id_account[0]['value'];
      }

      $entities_kt_calculation = \Drupal::entityTypeManager()
        ->getStorage('kt_calculation')
        ->loadByProperties([
          'drupal_account_id_field' => $drupal_id_account,
        ]);

      $entities_kt_account = \Drupal::entityTypeManager()
        ->getStorage('account')
        ->loadByProperties([
          'account_number_field' => $ktuser_inputs,
        ]);

      $last_array_of_entity_kt_calculation = end($entities_kt_calculation);

      $debt_field = $last_array_of_entity_kt_calculation->get('debt_field');
      $entity_array_debt = $debt_field->getValue();

      foreach ($entities_kt_account as $entity_kt_account) {
        $apartment_array_field = $entity_kt_account->get('apartment_number_field')->getValue();
      }
    }

    $form['message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Введіть номер рахунку'),
      '#placeholder' => 'Номер рахунку',
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Пошук'),
      ],
    ];

    // $counter = \Drupal::service('entity_type.manager')->getStorage('kt_counter')->load(12430);
    if (isset($ktuser_inputs['message']) && $form_state->isSubmitted()) {

      $bill = $ktuser_inputs['message'];

      $form['bill'] = [
        '#type' => 'item',
        '#title' => 'Рахунок' . ' ' . $bill,
      ];

      $form['debt'] = [
        '#type' => 'item',
        '#title' => 'Заборгованість по рахунку:' . ' ' . $entity_array_debt[0]['value'],
      ];

      $form['apartment'] = [
        '#type' => 'item',
        '#title' => 'Номер квартири (для перевірки):' . ' ' . $apartment_array_field[0]['value'],
      ];

      /** @var \Drupal\kt_counter\Entity\KtCounter $entity_kt_counter */
      foreach ($entities_kt_counters as $entity_kt_counter) {

        /** @var \Drupal\Core\Field\FieldItemListInterface $info_field */
        $info_field = $entity_kt_counter->get('info_field');
        $entity_array_info = $info_field->getValue();
        $entity_array_date_checking = $entity_kt_counter->get('next_date_checking_field')->getValue();
        $entity_array_account = $entity_kt_counter->get('id')->getValue();
        $entity_account = $entity_array_account[0]['value'];

        $form['counter_number'][$entity_account] = [
          '#type' => 'item',
            // $this->t('Hello'),
          '#title' => $entity_array_info[0]['value'],
          '#markup' => $entity_array_date_checking[0]['value'],
        ];

        $test = 1;
      }

      $rows = [];
      foreach ($entities_kt_calculation as $entity_kt_calculation) {
        $entity_array_bill_month = $entity_kt_calculation->get('billing_month_field')->getValue();
        $entity_array_sum_payment = $entity_kt_calculation->get('sum_payment_field')->getValue();
        $entity_array_paid_field = $entity_kt_calculation->get('paid_field')->getValue();
        $entity_array_debt_field = $entity_kt_calculation->get('debt_field')->getValue();
        $entity_array_row_field = $entity_kt_calculation->get('details_field')->getValue();

        $test = $entity_array_row_field[0]['value'];


        if ($entity_array_row_field[0]['value'] == 0) {
          $rows[] = [
            'Розрахунковий місяць' => $entity_array_bill_month[0]['value'],
            'Нараховано' => $entity_array_sum_payment[0]['value'],
            'Сплачено' => $entity_array_paid_field[0]['value'],
            'Борг' => $entity_array_debt_field[0]['value'],
          ];
        } else {
          $rows[] = [
            'Розрахунковий місяць' => $entity_array_bill_month[0]['value'],
            'Нараховано' => ' ',
            'Сплачено' => $entity_array_paid_field[0]['value'],
            'Борг' => ' ',
          ];
        }
      }


      $form['contacts'] = [
        '#type' => 'table',
        '#caption' => $this->t('Example'),
        '#header' => [
          $this->t('Рохрахунковий місяць'),
          $this->t('Нараховано'),
          $this->t('Сплачено'),
          $this->t('Борг'),
        ],
        '#rows' => $rows,

      ];


    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {

    if (is_null($form_state->getValue('message')) || empty($form_state->getValue('message'))) {
      $form_state->setErrorByName(
        'message',
        $this->t('Message should not be empty.'),
      );
    }

    if (mb_strlen($form_state->getValue('message')) < 3) {
      $form_state->setErrorByName(
        'message',
        $this->t('Message should be at least 3 characters.'),
      );
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    // $form_state->setRedirect('ktpersonal.ktpersonal');
    $form_state->setRebuild();
  }

}
