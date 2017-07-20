<?php

namespace Drupal\dashboard_project\Plugin\rest\EntityDecorator;

use Drupal\advanced_rest\Annotation\AdvancedRestEntityDecorator;
use Drupal\advanced_rest\Plugin\RestEntityDecoratorBase;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

/**
 * Class MonitoringProjectEntityDecorator.
 *
 * @AdvancedRestEntityDecorator(
 *   id = "project",
 *   name = @Translation("Rest Entity Decorator - Monitoring Project"),
 *   entityType = "project",
 *   bundle = "monitoring"
 * )
 */
class MonitoringProjectEntityDecorator extends RestEntityDecoratorBase {

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    return [
      'key' => $this->get('key_unique'),
      'title' => $this->get('name'),
      'weight' => $this->get('field_monitor_weight'),
      'logo' => $this->getLogo(),
      'description' => $this->get('field_monitor_description', 'value'),
      'link' => $this->getLinks(),
      'indicators' => $this->getDecoratedReferencedEntities('field_monitor_indicator'),
    ];
  }

  /**
   * Get logo.
   *
   * @return null|string
   *   The logo URL.
   */
  public function getLogo() {
    $fid = $this->get('field_monitor_logo', 'target_id');
    $storage = $this->entityTypeManager->getStorage('file');
    $files = $storage->loadByProperties(['fid' => $fid]);
    if (empty($files)) {
      return NULL;
    }

    $logo = reset($files);
    return file_create_url($logo->getFileUri());
  }

  /**
   * Get links.
   *
   * @return array
   *   An array of links.
   */
  protected function getLinks() {
    $links = $this->get('field_monitor_link');
    if (isset($links['uri'])) {
      $links = [$links];
    }

    foreach ($links as &$link) {
      $link = [
        'url' => $link['uri'],
        'label' => $link['title'],
      ];
    }
    return $links;
  }

}
