<?php

namespace Drupal\dashboard_widget;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Dashboard widget entities.
 *
 * @ingroup dashboard_widget
 */
class DashboardWidgetListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Dashboard widget ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dashboard_widget\Entity\DashboardWidget */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dashboard_widget.edit_form', array(
          'dashboard_widget' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
