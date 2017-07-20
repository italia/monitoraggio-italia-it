<?php

namespace Drupal\dashboard_dataset;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Dataset entity.
 *
 * @see \Drupal\dashboard_dataset\Entity\DashboardDataset.
 */
class DashboardDatasetAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\dashboard_dataset\DashboardDatasetInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished dataset entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published dataset entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit dataset entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete dataset entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add dataset entities');
  }

}
