<?php

namespace Drupal\dashboard_indicator;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Dashboard indicator entities.
 *
 * @ingroup dashboard_indicator
 */
class DashboardIndicatorListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Dashboard indicator ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dashboard_indicator\Entity\DashboardIndicator */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.dashboard_indicator.edit_form', array(
          'dashboard_indicator' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
