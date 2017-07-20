<?php

namespace Drupal\dashboard_dataset\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dashboard_dataset\DashboardDatasetTypeInterface;

/**
 * Defines the Dataset type entity.
 *
 * @ConfigEntityType(
 *   id = "dataset_type",
 *   label = @Translation("Dataset type"),
 *   handlers = {
 *     "list_builder" = "Drupal\dashboard_dataset\DashboardDatasetTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\dashboard_dataset\Form\DashboardDatasetTypeForm",
 *       "edit" = "Drupal\dashboard_dataset\Form\DashboardDatasetTypeForm",
 *       "delete" = "Drupal\dashboard_dataset\Form\DashboardDatasetTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\dashboard_dataset\DashboardDatasetTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "dataset_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "dataset",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dataset_type/{dataset_type}",
 *     "add-form" = "/admin/structure/dataset_type/add",
 *     "edit-form" = "/admin/structure/dataset_type/{dataset_type}/edit",
 *     "delete-form" = "/admin/structure/dataset_type/{dataset_type}/delete",
 *     "collection" = "/admin/structure/dataset_type"
 *   }
 * )
 */
class DashboardDatasetType extends ConfigEntityBundleBase implements DashboardDatasetTypeInterface {
  /**
   * The Dataset type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Dataset type label.
   *
   * @var string
   */
  protected $label;

}
