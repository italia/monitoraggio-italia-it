<?php

namespace Drupal\dashboard_widget;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Dashboard widget entities.
 *
 * @ingroup dashboard_widget
 */
interface DashboardWidgetInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.
  /**
   * Gets the Dashboard widget type.
   *
   * @return string
   *   The Dashboard widget type.
   */
  public function getType();

  /**
   * Gets the Dashboard widget name.
   *
   * @return string
   *   Name of the Dashboard widget.
   */
  public function getName();

  /**
   * Sets the Dashboard widget name.
   *
   * @param string $name
   *   The Dashboard widget name.
   *
   * @return \Drupal\dashboard_widget\DashboardWidgetInterface
   *   The called Dashboard widget entity.
   */
  public function setName($name);

  /**
   * Gets the Dashboard widget creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Dashboard widget.
   */
  public function getCreatedTime();

  /**
   * Sets the Dashboard widget creation timestamp.
   *
   * @param int $timestamp
   *   The Dashboard widget creation timestamp.
   *
   * @return \Drupal\dashboard_widget\DashboardWidgetInterface
   *   The called Dashboard widget entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Dashboard widget published status indicator.
   *
   * Unpublished Dashboard widget are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Dashboard widget is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Dashboard widget.
   *
   * @param bool $published
   *   TRUE to set this Dashboard widget to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\dashboard_widget\DashboardWidgetInterface
   *   The called Dashboard widget entity.
   */
  public function setPublished($published);

}
