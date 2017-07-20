<?php

namespace Drupal\dashboard_project;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Project entity.
 *
 * @see \Drupal\dashboard_project\Entity\DashboardProject.
 */
class DashboardProjectAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dashboard_project\DashboardProjectInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished project entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published project entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit project entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete project entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add project entities');
  }

}
