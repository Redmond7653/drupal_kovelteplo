<?php

/* шлях файла web/modules/custom/ТВІЙ_МОДУЛЬ/src/Plugin/migrate/process/ТвійКласс.php */

namespace Drupal\ktpersonal\Plugin\migrate\process;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Drush\Drush;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * This plugin checks if a given entity exists.
 *
 * Example usage with configuration:
 * @code
 *   id:
 *     plugin: id_твого_плагіна
 *     source: some_id_from_csv
 * @endcode
 *
 * @MigrateProcessPlugin(
 *  id = "kt_logs_transform_id"
 * )
 */
class TransformId extends ProcessPluginBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * EntityExists constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration = NULL) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    // приклад як читати значення із строки csv:
    $property_counter_id = $row->get('counter_id');
    $property_color_name = $row->get('color_name');
    $property_brand_name = $row->get('brand_name');

    if (!empty($property_counter_id)) {
      $counter = $this->entityTypeManager->getStorage('ktpersonal_kt_counter')
        ->loadByProperties(
          ['counter_id' => $property_counter_id]);
      $counter = reset($counter);
      if ($counter) {
        // повертаєм айді лічильника.
        return $counter->id();
      }
    }

    $message = "Not found #{$property_counter_id}'";
    Drush::output()->writeln("\n" . $message);

    // Log the value.
    $migrate_executable->saveMessage($message);
    return NULL;
  }

}
