<?php

namespace Drupal\dashboard_widget\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dashboard_widget\DashboardWidgetTypeInterface;

/**
 * Defines the Dashboard widget type entity.
 *
 * @ConfigEntityType(
 *   id = "dashboard_widget_type",
 *   label = @Translation("Dashboard widget type"),
 *   handlers = {
 *     "list_builder" = "Drupal\dashboard_widget\DashboardWidgetTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\dashboard_widget\Form\DashboardWidgetTypeForm",
 *       "edit" = "Drupal\dashboard_widget\Form\DashboardWidgetTypeForm",
 *       "delete" = "Drupal\dashboard_widget\Form\DashboardWidgetTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\dashboard_widget\DashboardWidgetTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "dashboard_widget_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "dashboard_widget",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dashboard_widget_type/{dashboard_widget_type}",
 *     "add-form" = "/admin/structure/dashboard_widget_type/add",
 *     "edit-form" = "/admin/structure/dashboard_widget_type/{dashboard_widget_type}/edit",
 *     "delete-form" = "/admin/structure/dashboard_widget_type/{dashboard_widget_type}/delete",
 *     "collection" = "/admin/structure/dashboard_widget_type"
 *   }
 * )
 */
class DashboardWidgetType extends ConfigEntityBundleBase implements DashboardWidgetTypeInterface {
  /**
   * The Dashboard widget type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Dashboard widget type label.
   *
   * @var string
   */
  protected $label;

}
