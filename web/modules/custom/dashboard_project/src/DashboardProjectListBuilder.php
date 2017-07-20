<?php

namespace Drupal\dashboard_project;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Project entities.
 *
 * @ingroup dashboard_project
 */
class DashboardProjectListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Project ID');
    $header['name'] = $this->t('Identifier');
    $header['title'] = $this->t('Title');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\dashboard_project\Entity\DashboardProject */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.project.edit_form', [
          'project' => $entity->id(),
        ]
      )
    );
    $row['title'] = $entity->getKeyUnique();
    return $row + parent::buildRow($entity);
  }

}
