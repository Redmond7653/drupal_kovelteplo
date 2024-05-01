<?php declare(strict_types = 1);

namespace Drupal\teplo_counter\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Teplo counter type configuration entity.
 *
 * @ConfigEntityType(
 *   id = "teplo_counter_type",
 *   label = @Translation("Teplo counter type"),
 *   label_collection = @Translation("Teplo counter types"),
 *   label_singular = @Translation("teplo counter type"),
 *   label_plural = @Translation("teplo counters types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count teplo counters type",
 *     plural = "@count teplo counters types",
 *   ),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\teplo_counter\Form\TeploCounterTypeForm",
 *       "edit" = "Drupal\teplo_counter\Form\TeploCounterTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "list_builder" = "Drupal\teplo_counter\TeploCounterTypeListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer teplo_counter types",
 *   bundle_of = "teplo_counter",
 *   config_prefix = "teplo_counter_type",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/teplo_counter_types/add",
 *     "edit-form" = "/admin/structure/teplo_counter_types/manage/{teplo_counter_type}",
 *     "delete-form" = "/admin/structure/teplo_counter_types/manage/{teplo_counter_type}/delete",
 *     "collection" = "/admin/structure/teplo_counter_types",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid",
 *   },
 * )
 */
final class TeploCounterType extends ConfigEntityBundleBase {

  /**
   * The machine name of this teplo counter type.
   */
  protected string $id;

  /**
   * The human-readable name of the teplo counter type.
   */
  protected string $label;

}
