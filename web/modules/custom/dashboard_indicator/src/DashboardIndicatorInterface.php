<?php

namespace Drupal\dashboard_indicator;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Dashboard indicator entities.
 *
 * @ingroup dashboard_indicator
 */
interface DashboardIndicatorInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.
  /**
   * Gets the Dashboard indicator type.
   *
   * @return string
   *   The Dashboard indicator type.
   */
  public function getType();

  /**
   * Gets the Dashboard indicator name.
   *
   * @return string
   *   Name of the Dashboard indicator.
   */
  public function getName();

  /**
   * Sets the Dashboard indicator name.
   *
   * @param string $name
   *   The Dashboard indicator name.
   *
   * @return \Drupal\dashboard_indicator\DashboardIndicatorInterface
   *   The called Dashboard indicator entity.
   */
  public function setName($name);

  /**
   * Gets the Dashboard indicator creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Dashboard indicator.
   */
  public function getCreatedTime();

  /**
   * Sets the Dashboard indicator creation timestamp.
   *
   * @param int $timestamp
   *   The Dashboard indicator creation timestamp.
   *
   * @return \Drupal\dashboard_indicator\DashboardIndicatorInterface
   *   The called Dashboard indicator entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Dashboard indicator published status indicator.
   *
   * Unpublished Dashboard indicator are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Dashboard indicator is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Dashboard indicator.
   *
   * @param bool $published
   *   TRUE to set this Dashboard indicator to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\dashboard_indicator\DashboardIndicatorInterface
   *   The called Dashboard indicator entity.
   */
  public function setPublished($published);

}
