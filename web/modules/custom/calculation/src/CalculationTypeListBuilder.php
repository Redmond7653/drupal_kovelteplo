<?php declare(strict_types = 1);

namespace Drupal\calculation;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of calculation type entities.
 *
 * @see \Drupal\calculation\Entity\CalculationType
 */
final class CalculationTypeListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader(): array {
    $header['label'] = $this->t('Label');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity): array {
    $row['label'] = $entity->label();
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    $build = parent::render();

    $build['table']['#empty'] = $this->t(
      'No calculation types available. <a href=":link">Add calculation type</a>.',
      [':link' => Url::fromRoute('entity.calculation_type.add_form')->toString()],
    );

    return $build;
  }

}
