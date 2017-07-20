<?php

/**
 * @file
 * Contains dashboard_widget.page.inc.
 *
 * Page callback for Dashboard widget entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Dashboard widget templates.
 *
 * Default template: dashboard_widget.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_dashboard_widget(array &$variables) {
  // Fetch DashboardWidget Entity Object.
  $dashboard_widget = $variables['elements']['#dashboard_widget'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

/**
* Prepares variables for a custom entity type creation list templates.
*
* Default template: dashboard_widget-content-add-list.html.twig.
*
* @param array $variables
*   An associative array containing:
*   - content: An array of dashboard_widget-types.
*
* @see block_content_add_page()
*/
function template_preprocess_dashboard_widget_content_add_list(&$variables) {
  $variables['types'] = array();
  $query = \Drupal::request()->query->all();
  foreach ($variables['content'] as $type) {
    $variables['types'][$type->id()] = array(
      'link' => Link::fromTextAndUrl($type->label(), new Url('entity.dashboard_widget.add_form', array(
        'dashboard_widget_type' => $type->id()
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
