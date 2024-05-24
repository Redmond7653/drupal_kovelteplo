<?php declare(strict_types = 1);

namespace Drupal\account;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining an account entity type.
 */
interface AccountInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
