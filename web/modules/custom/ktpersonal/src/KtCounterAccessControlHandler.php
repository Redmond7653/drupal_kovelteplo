<?php declare(strict_types = 1);

namespace Drupal\ktpersonal;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines the access control handler for the kt_counter entity type.
 *
 * phpcs:disable Drupal.Arrays.Array.LongLineDeclaration
 *
 * @see https://www.drupal.org/project/coder/issues/3185082
 */
final class KtCounterAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account): AccessResult {
    return match($operation) {
      'view' => AccessResult::allowedIfHasPermissions($account, ['view ktpersonal_kt_counter', 'administer ktpersonal_kt_counter'], 'OR'),
      'update' => AccessResult::allowedIfHasPermissions($account, ['edit ktpersonal_kt_counter', 'administer ktpersonal_kt_counter'], 'OR'),
      'delete' => AccessResult::allowedIfHasPermissions($account, ['delete ktpersonal_kt_counter', 'administer ktpersonal_kt_counter'], 'OR'),
      default => AccessResult::neutral(),
    };
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL): AccessResult {
    return AccessResult::allowedIfHasPermissions($account, ['create ktpersonal_kt_counter', 'administer ktpersonal_kt_counter'], 'OR');
  }

}
