<?php

declare(strict_types=1);

namespace Drupal\ktpersonal\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\ktpersonal\CounterLogInterface;

/**
 * Defines the counterlog entity class.
 *
 * @ContentEntityType(
 *   id = "ktpersonal_counterlog",
 *   label = @Translation("CounterLog"),
 *   label_collection = @Translation("CounterLogs"),
 *   label_singular = @Translation("counterlog"),
 *   label_plural = @Translation("counterlogs"),
 *   label_count = @PluralTranslation(
 *     singular = "@count counterlogs",
 *     plural = "@count counterlogs",
 *   ),
 *   handlers = {
 *     "list_builder" = "Drupal\ktpersonal\CounterLogListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\ktpersonal\Form\CounterLogForm",
 *       "edit" = "Drupal\ktpersonal\Form\CounterLogForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *       "delete-multiple-confirm" = "Drupal\Core\Entity\Form\DeleteMultipleForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\ktpersonal\Routing\CounterLogHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "ktpersonal_counterlog",
 *   admin_permission = "administer ktpersonal_counterlog",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "id",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "collection" = "/admin/content/counterlog",
 *     "add-form" = "/counterlog/add",
 *     "canonical" = "/counterlog/{ktpersonal_counterlog}",
 *     "edit-form" = "/counterlog/{ktpersonal_counterlog}",
 *     "delete-form" = "/counterlog/{ktpersonal_counterlog}/delete",
 *     "delete-multiple-form" = "/admin/content/counterlog/delete-multiple",
 *   },
 *   field_ui_base_route = "entity.ktpersonal_counterlog.settings",
 * )
 */
final class CounterLog extends ContentEntityBase implements CounterLogInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the counterlog was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['info'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Інформація про лічильник'))
      ->setSetting('target_type', 'ktpersonal_kt_counter')
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'placeholder' => '',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['last_data'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Показники'))
      ->setRequired(FALSE)
      ->setTranslatable(TRUE)
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
      ])
      ->setDisplayOptions('form', [
        'type' => 'integer_number',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    return $fields;
  }




}
