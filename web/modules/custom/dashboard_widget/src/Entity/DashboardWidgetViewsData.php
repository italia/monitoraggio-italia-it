<?php

namespace Drupal\dashboard_widget\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Dashboard widget entities.
 */
class DashboardWidgetViewsData extends EntityViewsData implements EntityViewsDataInterface {
  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['dashboard_widget']['table']['base'] = array(
      'field' => 'id',
      'title' => $this->t('Dashboard widget'),
      'help' => $this->t('The Dashboard widget ID.'),
    );

    return $data;
  }

}
