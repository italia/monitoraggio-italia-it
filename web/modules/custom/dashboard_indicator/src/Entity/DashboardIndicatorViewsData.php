<?php

namespace Drupal\dashboard_indicator\Entity;

use Drupal\views\EntityViewsData;
use Drupal\views\EntityViewsDataInterface;

/**
 * Provides Views data for Dashboard indicator entities.
 */
class DashboardIndicatorViewsData extends EntityViewsData implements EntityViewsDataInterface {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    $data['dashboard_indicator']['table']['base'] = [
      'field' => 'id',
      'title' => $this->t('Dashboard indicator'),
      'help' => $this->t('The Dashboard indicator ID.'),
    ];

    $data['dashboard_indicator']['indicator_csv_link'] = [
      'title' => $this->t('Dashboard Indicator CSV Link'),
      'help' => $this->t('The link to the CSV of the Indicator.'),
      'field' => [
        'field' => 'id',
        'id' => 'indicator_csv_link',
      ],
    ];

    return $data;
  }

}
