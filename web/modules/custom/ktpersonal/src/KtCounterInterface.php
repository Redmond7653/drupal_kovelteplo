<?php declare(strict_types = 1);

namespace Drupal\ktpersonal;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a kt_counter entity type.
 */
interface KtCounterInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
