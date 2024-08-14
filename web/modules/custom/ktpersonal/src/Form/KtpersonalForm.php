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

    // If (!empty($ktuser_inputs)) {
    //
    //      /** @var \Drupal\ktpersonal\Entity\KtAccount[] $entities_kt_account */
    //      if ($entities_kt_account = \Drupal::entityTypeManager()
    //        ->getStorage('ktpersonal_kt_account')
    //        ->loadByProperties([
    //          'account_number' => $ktuser_inputs,
    //        ])) {
    //        $entities_kt_account = reset($entities_kt_account);
    //        $drupal_id_account = $entities_kt_account->id();
    //        // Foreach ($entities_kt_account as $entity_account) {
    //        //        $drupal_array_id_account = $entity_account->get('id')
    //        //          ->getValue();
    //        //        $drupal_id_account = $drupal_array_id_account[0]['value'];
    //        //      }.
    //        $entities_kt_counters = \Drupal::entityTypeManager()
    //          ->getStorage('ktpersonal_kt_counter')
    //          ->loadByProperties([
    //            'kt_accounts_drupal_id' => $drupal_id_account,
    //          ]);
    //
    //        $entities_kt_calculation = \Drupal::entityTypeManager()
    //          ->getStorage('ktpersonal_kt_calculation')
    //          ->loadByProperties([
    //            'kt_owner' => $drupal_id_account,
    //          ]);
    //
    //        $last_array_of_entity_kt_calculation = end($entities_kt_calculation);
    //
    //        $debt_field = $last_array_of_entity_kt_calculation->get('debt');
    //        $entity_array_debt = $debt_field->getValue();
    //
    //        $apartment_array_field = $entities_kt_account->get('apartment_number')->getValue();
    //      }
    //    }
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

    // If (isset($ktuser_inputs['message']) && $form_state->isSubmitted()) {
    //
    //
    //      $view_builder = \Drupal::entityTypeManager()->getViewBuilder('ktpersonal_kt_account');
    //      $output = $view_builder->view($entities_kt_account, 'short_ktaccount_info');
    //
    //      $form['display_entity'] = $output;
    //
    //      $form['view_account_info'] = [
    //        '#type' => 'view',
    //        '#name' => 'ktpersonal_ktaccount_info',
    //        '#display_id' => 'kt_personal_account_info_block',
    //        '#arguments' => $ktuser_inputs,
    //      ];
    //
    //      $form['view_debt'] = [
    //        '#type' => 'view',
    //        '#name' => 'ktpersonal_ktcalculation_debt',
    //        '#display_id' => 'debt_block',
    //        '#arguments' => $ktuser_inputs,
    //      ];
    //
    //      $form['view_counter'] = [
    //        '#type' => 'view',
    //        '#name' => 'ktpersonal_ktcounter',
    //        '#display_id' => 'kt_personal_counter_block',
    //        '#arguments' => $ktuser_inputs,
    //      ];
    //
    //      $form['view_calculation'] = [
    //        '#type' => 'view',
    //        '#name' => 'ktpersonal_ktcalculation',
    //        '#display_id' => 'kt_personal_calculation_block',
    //        '#arguments' => $ktuser_inputs,
    //      ];
    //
    //    }.
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {

    $validate_user_input = \Drupal::entityTypeManager()
      ->getStorage('ktpersonal_kt_account')
      ->loadByProperties([
        'account_number' => $form_state->getValue('message'),
      ]);

    if (empty($validate_user_input)) {
      $form_state->setErrorByName(
        'message',
        $this->t('Номер особового рахунку не знайдено')
      );
    }

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
    $ktuser_all_inputs = $form_state->getUserInput();
    $ktuser_account_number = $ktuser_all_inputs['message'];
    $this->messenger()->addStatus($this->t('The message has been sent.'));
    // $form_state->setRedirect('ktpersonal.ktpersonal');
    //    $form_state->setRebuild();
    $form_state->setRedirect('ktpersonal.ktpersonal_build', ['account' => $ktuser_account_number]);
  }

}



