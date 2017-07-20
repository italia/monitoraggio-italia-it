<?php

namespace Drupal\dashboard_widget;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Dashboard widget entity.
 *
 * @see \Drupal\dashboard_widget\Entity\DashboardWidget.
 */
class DashboardWidgetAccessControlHandler extends EntityAccessControlHandler {
  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dashboard_widget\DashboardWidgetInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dashboard widget entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dashboard widget entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dashboard widget entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete dashboard widget entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add dashboard widget entities');
  }

}
