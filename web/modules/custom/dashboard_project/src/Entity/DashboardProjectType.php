<?php

namespace Drupal\dashboard_project\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\dashboard_project\DashboardProjectTypeInterface;

/**
 * Defines the Project type entity.
 *
 * @ConfigEntityType(
 *   id = "project_type",
 *   label = @Translation("Project type"),
 *   handlers = {
 *     "list_builder" = "Drupal\dashboard_project\DashboardProjectTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\dashboard_project\Form\DashboardProjectTypeForm",
 *       "edit" = "Drupal\dashboard_project\Form\DashboardProjectTypeForm",
 *       "delete" = "Drupal\dashboard_project\Form\DashboardProjectTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\dashboard_project\DashboardProjectTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "project_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "project",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/project_type/{project_type}",
 *     "add-form" = "/admin/structure/project_type/add",
 *     "edit-form" = "/admin/structure/project_type/{project_type}/edit",
 *     "delete-form" = "/admin/structure/project_type/{project_type}/delete",
 *     "collection" = "/admin/structure/project_type"
 *   }
 * )
 */
class DashboardProjectType extends ConfigEntityBundleBase implements DashboardProjectTypeInterface {
  /**
   * The Project type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Project type label.
   *
   * @var string
   */
  protected $label;

}
