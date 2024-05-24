<?php declare(strict_types = 1);

namespace Drupal\calculation\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Calculation type configuration entity.
 *
 * @ConfigEntityType(
 *   id = "calculation_type",
 *   label = @Translation("Calculation type"),
 *   label_collection = @Translation("Calculation types"),
 *   label_singular = @Translation("calculation type"),
 *   label_plural = @Translation("calculations types"),
 *   label_count = @PluralTranslation(
 *     singular = "@count calculations type",
 *     plural = "@count calculations types",
 *   ),
 *   handlers = {
 *     "form" = {
 *       "add" = "Drupal\calculation\Form\CalculationTypeForm",
 *       "edit" = "Drupal\calculation\Form\CalculationTypeForm",
 *       "delete" = "Drupal\Core\Entity\EntityDeleteForm",
 *     },
 *     "list_builder" = "Drupal\calculation\CalculationTypeListBuilder",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   admin_permission = "administer calculation types",
 *   bundle_of = "calculation",
 *   config_prefix = "calculation_type",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "add-form" = "/admin/structure/calculation_types/add",
 *     "edit-form" = "/admin/structure/calculation_types/manage/{calculation_type}",
 *     "delete-form" = "/admin/structure/calculation_types/manage/{calculation_type}/delete",
 *     "collection" = "/admin/structure/calculation_types",
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "uuid",
 *   },
 * )
 */
final class CalculationType extends ConfigEntityBundleBase {

  /**
   * The machine name of this calculation type.
   */
  protected string $id;

  /**
   * The human-readable name of the calculation type.
   */
  protected string $label;

}
