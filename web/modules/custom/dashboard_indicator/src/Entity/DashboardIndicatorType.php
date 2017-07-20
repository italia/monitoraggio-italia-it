<?php

namespace Drupal\dashboard_indicator\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dashboard_indicator\DashboardIndicatorTypeInterface;

/**
 * Defines the Dashboard indicator type entity.
 *
 * @ConfigEntityType(
 *   id = "dashboard_indicator_type",
 *   label = @Translation("Dashboard indicator type"),
 *   handlers = {
 *     "list_builder" = "Drupal\dashboard_indicator\DashboardIndicatorTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\dashboard_indicator\Form\DashboardIndicatorTypeForm",
 *       "edit" = "Drupal\dashboard_indicator\Form\DashboardIndicatorTypeForm",
 *       "delete" = "Drupal\dashboard_indicator\Form\DashboardIndicatorTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\dashboard_indicator\DashboardIndicatorTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "dashboard_indicator_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "dashboard_indicator",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "key_unique" = "key",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dashboard_indicator_type/{dashboard_indicator_type}",
 *     "add-form" = "/admin/structure/dashboard_indicator_type/add",
 *     "edit-form" = "/admin/structure/dashboard_indicator_type/{dashboard_indicator_type}/edit",
 *     "delete-form" = "/admin/structure/dashboard_indicator_type/{dashboard_indicator_type}/delete",
 *     "collection" = "/admin/structure/dashboard_indicator_type"
 *   }
 * )
 */
class DashboardIndicatorType extends ConfigEntityBundleBase implements DashboardIndicatorTypeInterface {
  /**
   * The Dashboard indicator type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Dashboard indicator type label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Dashboard indicator key_unique label.
   *
   * @var string
   */
  protected $key_unique;

}
