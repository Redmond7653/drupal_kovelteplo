<?php declare(strict_types = 1);

namespace Drupal\kt_counter;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a kt counter entity type.
 */
interface KtCounterInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
