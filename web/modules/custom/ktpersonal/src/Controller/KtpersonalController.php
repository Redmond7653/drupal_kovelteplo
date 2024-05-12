<?php

namespace Drupal\ktpersonal\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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

//    $form['submit'] = [
//      '#type' => 'submit',
//      '#value' => 'Пошук',
//
//    ];

    return $form;
  }

  /**
   *
   */
  public function ktanswer() {

    $output = [];


    return $output;
  }

   public function validateForm(array &$form, FormStateInterface $formState) {
    $z = 0;
}

}
