<?php declare(strict_types = 1);

namespace Drupal\calculation;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a calculation entity type.
 */
interface CalculationInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
