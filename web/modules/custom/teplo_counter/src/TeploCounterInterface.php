<?php declare(strict_types = 1);

namespace Drupal\teplo_counter;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a teplo counter entity type.
 */
interface TeploCounterInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
