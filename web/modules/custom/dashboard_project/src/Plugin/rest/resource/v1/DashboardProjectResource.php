<?php

namespace Drupal\dashboard_project\Plugin\rest\resource\v1;

use Drupal\advanced_rest\Plugin\AdvancedResourceBase;
use Drupal\dashboard_project\Entity\DashboardProject;

/**
 * Retrieves a Project structure by its unique key.
 *
 * @RestResource(
 *   id = "project_resource",
 *   label = @Translation("Dashboard - Projects"),
 *   uri_paths = {
 *     "canonical" = "/v1/category/{project_key}",
 *   }
 * )
 */
class DashboardProjectResource extends AdvancedResourceBase {

  /**
   * Responds to GET requests.
   *
   * Returns an object containing all project info with datasets and indicators.
   *
   * @param \Drupal\dashboard_project\Entity\DashboardProject $project
   *   The project.
   *
   * @return \Drupal\rest\ResourceResponse
   *   A Rest resource response.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get(DashboardProject $project) {
    $data = $this->entityDecoratorFactory->get($project)->toArray();

    $indicator = $this->extract($data['indicators'], 'indicator');
    if ($indicator === FALSE) {
      return $this->buildResponse($data);
    }
    $data = $indicator;

    $dataset = $this->extract($data['datasets'], 'dataset');
    if ($dataset === FALSE) {
      return $this->buildResponse($data);
    }
    $data = $dataset;

    return $this->buildResponse($data);
  }

  /**
   * Helper method: extract elements form data by parameter.
   *
   * @param array $data
   *   An array of data to be processed.
   * @param string $param_key
   *   The parameter key.
   *
   * @return array|false
   *   An array of extracted data or FALSE if the provided param does not exits.
   */
  protected function extract(array $data, $param_key) {
    $param = $this->getParameter($param_key);
    if (empty($param)) {
      return FALSE;
    }

    $result = [];
    foreach ($data as $item) {
      if (isset($item['key']) && ($item['key'] == $param)) {
        $result = $item;
        break;
      }
    }
    return $result;
  }

}
