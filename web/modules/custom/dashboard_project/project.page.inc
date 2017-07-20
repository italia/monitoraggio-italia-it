<?php

/**
 * @file
 * Contains project.page.inc.
 *
 * Page callback for Project entities.
 */

use Drupal\Core\Render\Element;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Prepares variables for Project templates.
 *
 * Default template: project.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_project(array &$variables) {
  // Fetch DashboardProject Entity Object.
  // $project = $variables['elements']['#project'];
  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
  // Add entity id and absolute URL to the template variables.
  $variables['entity_id'] = $variables['elements']['#project']->id();
  $variables['entity_url'] = $variables['elements']['#project']->toUrl('canonical', ['absolute' => TRUE])->toString();

  // Config for DESI element.
  $config = \Drupal::config('dashboard_project.desisettings');
  if (empty($config)) {
    // Logs error.
    \Drupal::logger('dash_desi_settings')->error('DESI settings: configuration empty.');
    return;
  }
  // Get link and label path for template.
  $variables['dash_desi_label'] = '';
  if (!empty($config->get('dash_desi_settings.desi_link_label'))) {
    $variables['dash_desi_label'] = $config->get('dash_desi_settings.desi_link_label');
  }
  $variables['dash_desi_path'] = '#';
  if (!empty($config->get('dash_desi_settings.desi_link_path'))) {
    $variables['dash_desi_path'] = $config->get('dash_desi_settings.desi_link_path');
  }
}

/**
 * Prepares variables for a custom entity type creation list templates.
 *
 * Default template: project-content-add-list.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - content: An array of project-types.
 *
 * @see block_content_add_page()
 */
function template_preprocess_project_content_add_list(array &$variables) {
  $variables['types'] = [];
  $query = \Drupal::request()->query->all();
  foreach ($variables['content'] as $type) {
    $variables['types'][$type->id()] = [
      'link' => Link::fromTextAndUrl($type->label(), new Url('entity.project.add_form', [
        'project_type' => $type->id(),
      ], ['query' => $query])),
      'description' => [
        '#markup' => $type->label(),
      ],
      'title' => $type->label(),
      'localized_options' => [
        'query' => $query,
      ],
    ];
  }
}
