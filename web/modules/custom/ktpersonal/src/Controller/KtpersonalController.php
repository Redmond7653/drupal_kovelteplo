<?php

namespace Drupal\ktpersonal\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Returns responses for Ktpersonal routes.
 */
final class KtpersonalController extends ControllerBase {

  /**
   * The controller constructor.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {}

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('entity_type.manager'),
    );
  }

  /**
   * Builds the response.
   */
  public function content(): array {

    $form['keys'] = [
      '#type' => 'textfield',
      '#title' => 'Введіть номер рахунку',
      '#description' => 'Type key words for searching.',
      '#placeholder' => 'Номер рахунку',
      '#default_value' => 'test',
    ];

    // $entities_kt_logs = \Drupal::entityTypeManager()
    //      ->getStorage('ktpersonal_counterlog')
    //      ->loadByProperties([
    //        'kt_accounts_drupal_id' => $drupal_id_account,
    //      ]);
    $time = strtotime(date('Y-m-d'));

    $query = \Drupal::entityTypeManager()
      ->getStorage('ktpersonal_counterlog')->getQuery();

    // $query = \Drupal::entityQuery('ktpersonal_counterlog');
    $query->condition('created', $time, '>=');
    $query->condition('created', $time + 24 * 60 * 60, '<');

    $query->accessCheck(FALSE);

    $result = $query->execute();

    $entities_kt_logs = \Drupal::entityTypeManager()
      ->getStorage('ktpersonal_counterlog')
      ->loadMultiple($result);

    $counter_data = [];
    $data = 'Код;НомерЛицьовогоРахунку;id;lastdata;updated' . "\n";
    foreach ($entities_kt_logs as $entities_kt_log) {

      $entity_counter_log_id = $entities_kt_log->get('info')->getValue();
      $entity_counter_log_last_data_array = $entities_kt_log->get('last_data')->value;
      $entity_counter_log_created_array = $entities_kt_log->get('created')->getValue();
      // $entity_counter_log_last_data = $entity_counter_log_last_data_array[0]['value'];
      $entity_counter_log_created = $entity_counter_log_created_array[0]['value'];

      $time_when_created = date('Y-m-d H:i', intval($entity_counter_log_created));

      $entities_kt_counter = \Drupal::entityTypeManager()
        ->getStorage('ktpersonal_kt_counter')
        ->load($entity_counter_log_id[0]['target_id']);

      $kt_counter_operation_code = $entities_kt_counter->get('operation_code')->value;
      $kt_counter_id = $entities_kt_counter->get('counter_id')->value;
      $kt_account_id = $entities_kt_counter->get('kt_accounts_drupal_id')->getValue();

      $entities_kt_account = \Drupal::entityTypeManager()
        ->getStorage('ktpersonal_kt_account')
        ->load($kt_account_id[0]['target_id']);

      $kt_account_number = $entities_kt_account->get('account_number')->value;

      $counter_data[] = $entities_kt_counter->get('operation_code')->value;
      $counter_data[] = $entities_kt_account->get('account_number')->value;
      $counter_data[] = $entities_kt_counter->get('counter_id')->value;
      $counter_data[] = $entities_kt_log->get('last_data')->value;
      $counter_data[] = $time_when_created;

      // $data = $data . $kt_counter_operation_code . ';' . $kt_account_number . ';' . $kt_counter_id . ';' . $entity_counter_log_last_data . ';' . $time_when_created . "\n";
      $data = $data . implode(';', $counter_data) . "\n";

      $counter_data = [];

    }

    // Generate response for given data file.
    $response = new Response($data, 200);

    $name = 'test';

    // Forcefully override Content-Length, Content-Disposition header values.
    $name = $name . '.csv';
    $response->headers->set('Content-Type', 'application/csv');
    $response->headers->set('Content-Length', strlen($data));
    $response->headers->set('Content-Disposition', 'attachment; filename=' . $name);

    $response->send();

    return $form;
  }

}
