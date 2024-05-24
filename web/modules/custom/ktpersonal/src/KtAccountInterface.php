<?php declare(strict_types = 1);

namespace Drupal\ktpersonal;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a kt_account entity type.
 */
interface KtAccountInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
