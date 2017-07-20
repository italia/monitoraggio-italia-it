<?php

/**
 * @file
 * Contains dashboard_indicator.page.inc.
 *
 * Page callback for Dashboard indicator entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Dashboard indicator templates.
 *
 * Default template: dashboard_indicator.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dashboard_indicator(array &$variables) {
  // Fetch DashboardIndicator Entity Object.
  $dashboard_indicator = $variables['elements']['#dashboard_indicator'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

/**
* Prepares variables for a custom entity type creation list templates.
*
* Default template: dashboard_indicator-content-add-list.html.twig.
*
* @param array $variables
*   An associative array containing:
*   - content: An array of dashboard_indicator-types.
*
* @see block_content_add_page()
*/
function template_preprocess_dashboard_indicator_content_add_list(&$variables) {
  $variables['types'] = array();
  $query = \Drupal::request()->query->all();
  foreach ($variables['content'] as $type) {
    $variables['types'][$type->id()] = array(
      'link' => Link::fromTextAndUrl($type->label(), new Url('entity.dashboard_indicator.add_form', array(
        'dashboard_indicator_type' => $type->id()
      ), array('query' => $query))),
      'description' => array(
      '#markup' => $type->label(),
      ),
      'title' => $type->label(),
      'localized_options' => array(
      'query' => $query,
      ),
    );
  }
}
