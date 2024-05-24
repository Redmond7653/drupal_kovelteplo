<?php declare(strict_types = 1);

namespace Drupal\teplo_counter;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of teplo counter type entities.
 *
 * @see \Drupal\teplo_counter\Entity\TeploCounterType
 */
final class TeploCounterTypeListBuilder extends ConfigEntityListBuilder {

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
      'No teplo counter types available. <a href=":link">Add teplo counter type</a>.',
      [':link' => Url::fromRoute('entity.teplo_counter_type.add_form')->toString()],
    );

    return $build;
  }

}
