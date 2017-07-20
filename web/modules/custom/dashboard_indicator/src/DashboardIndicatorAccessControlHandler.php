<?php

namespace Drupal\dashboard_indicator;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Dashboard indicator entity.
 *
 * @see \Drupal\dashboard_indicator\Entity\DashboardIndicator.
 */
class DashboardIndicatorAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dashboard_indicator\DashboardIndicatorInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dashboard indicator entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dashboard indicator entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dashboard indicator entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete dashboard indicator entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add dashboard indicator entities');
  }

}
