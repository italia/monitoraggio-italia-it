<?php

namespace Drupal\rest_elements\Plugin\rest\resource\v1;

use Drupal\advanced_rest\AdvancedRestEntityDecoratorFactoryInterface;
use Drupal\advanced_rest\Plugin\AdvancedResourceBase;
use Drupal\advanced_rest\ResourceDescriptionHandlerInterface;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\TypedData\TypedDataManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Retrieves dom elements grouped by its context.
 *
 * @RestResource(
 *   id = "rest_elements_resource",
 *   label = @Translation("Rest elements resource"),
 *   uri_paths = {
 *     "canonical" = "/v1/rest-elements",
 *   }
 * )
 */
class RestElementsResource extends AdvancedResourceBase {

  /**
   * Class MenuLinkTree.
   *
   * @var \Drupal\Core\Menu\MenuLinkTree
   */
  protected $menuTree;

  /**
   * Class ConfigFactory.
   *
   * @var Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * Keyed array contains the output structure.
   *
   * @var array
   */
  protected $build;

  /**
   * RestElementsResource constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   A current user instance.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The Request Stack.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The Entity Type Manager.
   * @param \Drupal\advanced_rest\ResourceDescriptionHandlerInterface $description_handler
   *   The Rest Resource Description Handler.
   * @param \Drupal\advanced_rest\AdvancedRestEntityDecoratorFactoryInterface $entity_decorator_factory
   *   The Rest Entity decorator factory.
   * @param \Drupal\Core\TypedData\TypedDataManagerInterface $typed_data_manager
   *   The Typed Data Manager.
   * @param \Drupal\Core\Menu\MenuLinkTreeInterface $menu_tree
   *   The menu tree interface.
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The config factory to retrieves every need configs.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    array $serializer_formats,
    LoggerInterface $logger,
    AccountProxyInterface $current_user,
    RequestStack $request_stack,
    EntityTypeManagerInterface $entity_type_manager,
    ResourceDescriptionHandlerInterface $description_handler,
    AdvancedRestEntityDecoratorFactoryInterface $entity_decorator_factory,
    TypedDataManagerInterface $typed_data_manager,
    MenuLinkTreeInterface $menu_tree,
    ConfigFactory $config_factory) {

    parent::__construct(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $serializer_formats,
      $logger,
      $current_user,
      $request_stack,
      $entity_type_manager,
      $description_handler,
      $entity_decorator_factory,
      $typed_data_manager);

    $this->menuTree = $menu_tree;
    $this->configFactory = $config_factory;
    $this->build = [
      'header' => [],
      'footer' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('current_user'),
      $container->get('request_stack'),
      $container->get('entity_type.manager'),
      $container->get('advanced_rest.resource_description'),
      $container->get('advanced_rest.entity_decorator_factory'),
      $container->get('typed_data_manager'),
      $container->get('menu.link_tree'),
      $container->get('config.factory')
    );
  }

  /**
   * Wrapper function inserting an element into the header section of the build.
   *
   * @param array $element
   *   Structured element for exporting.
   */
  protected function addHeaderElement(array $element) {
    $this->addElement($element, 'header');
  }

  /**
   * Wrapper function inserting an element into the footer section of the build.
   *
   * @param array $element
   *   Structured element for exporting.
   */
  protected function addFooterElement(array $element) {
    $this->addElement($element, 'footer');
  }

  /**
   * Inserts element into the specified section of the response skeleton.
   *
   * @param array $element
   *   Structured element for exporting.
   * @param string $section
   *   Main section possible values: 'header' or 'footer'.
   */
  protected function addElement(array $element, $section) {
    $this->build[$section] += $element;
  }

  /**
   * Respons to GET requests.
   *
   * Returns an object containing all elements grouped by its context.
   *
   * @return \Drupal\rest\ResourceResponse
   *   A Rest resource response.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function get() {

    // Loading banner.
    $structure = $this->exportElement('banner', 'banner');
    $this->addHeaderElement($structure);

    // Loading sitename.
    $structure = $this->exportElement('sitename', FALSE, 'title');
    $this->addHeaderElement($structure);

    // Loading the main menu structure.
    $structure = $this->exportElement('menu', 'main', 'menu');
    $this->addHeaderElement($structure);

    // Loading the footer menu structure.
    $structure = $this->exportElement('menu', 'footer', 'footer_links');
    $this->addFooterElement($structure);

    // Loading the site-map menu structure.
    $structure = $this->exportElement('menu', 'site-map', 'site_map');
    $this->addFooterElement($structure);

    // Loading the contacts menu structure.
    $structure = $this->exportElement('contacts', 'contacts');
    $this->addFooterElement($structure);

    // Loading the socials menu structure.
    $structure = $this->exportElement('socials', 'socials');
    $this->addFooterElement($structure);

    return $this->buildResponse($this->build);
  }

  /**
   * {@inheritdoc}
   */
  public function buildResponse($data = NULL, $status = 200, array $headers = ['Content-Type' => 'application/json']) {
    return new JsonResponse($data, $status, $headers);
  }

  /**
   * Starting function to exporting element identified by type and name.
   *
   * @param string $element_type
   *   Element type.
   * @param string $element_name
   *   Element name element name to retrieve.
   *   This parameter will be used to keyed the structure.
   *   If $element_key is not set.
   * @param string $element_key
   *   Key where the exported structure will be keyed.
   *   $element_name will be used otherwise.
   *
   * @return array
   *   Keyed by element_name or element_key.
   *
   * @throws \InvalidArgumentException
   *   Throws exception expected.
   */
  protected function exportElement($element_type, $element_name = '', $element_key = '') {
    if (empty($element_name) && empty($element_key)) {
      throw new \InvalidArgumentException('The element-name and element-key cannot be empty');
    }
    $export_key = $element_key ?: $element_name;
    switch ($element_type) {
      case 'menu':
        $out = $this->exportMenu($element_name);
        break;

      case 'sitename':
        $out = $this->exportSitename();
        break;

      case 'banner':
        $out = $this->exportBanner();
        break;

      case 'contacts':
        $out = $this->exportContacts();
        break;

      case 'socials':
        $out = $this->exportSocials();
        break;
    }
    return [$export_key => $out];
  }

  /**
   * Building of export menu structure.
   *
   * @param string $menu_name
   *   Menu name.
   *
   * @return array
   *   Keyed by machine name Example: ['main' => [...]].
   *
   * @throws \InvalidArgumentException
   *   Throws exception expected.
   */
  public function exportMenu($menu_name) {
    if (empty($menu_name)) {
      throw new \InvalidArgumentException('The parameter cannot be empty');
    }
    $parameters = $this->menuTree
      ->getCurrentRouteMenuTreeParameters($menu_name)
      ->onlyEnabledLinks();

    $menu = $this->menuTree->load($menu_name, $parameters);
    $manipulators = [
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $menu = $this->menuTree->transform($menu, $manipulators);
    return $this->exportMenuItems($menu);
  }

  /**
   * Building of export site-name structure.
   *
   * @return array
   *   Keyed array
   */
  public function exportSitename() {
    $sitename = $this->configFactory->get('system.site')->getOriginal('name');
    return ['label' => $sitename];
  }

  /**
   * Building of export banner structure.
   *
   * @return array
   *   Keyed array
   */
  public function exportBanner() {
    $settings = $this->configFactory->get('dashboard.settings')
      ->get('header_settings');
    return [
      'label' => $settings['italiagov_afferent_administration_name'],
      'path' => $settings['italiagov_afferent_administration_url'],
    ];
  }

  /**
   * Building of export socials structure.
   *
   * @return array
   *   Keyed array
   */
  public function exportSocials() {
    $settings = $this->configFactory->get('dashboard.settings')
      ->get('social_settings');
    $items = [];
    $skip_keys = ['italiagov_socials_title', 'italiagov_socials_locations'];
    foreach ($settings as $key => $setting) {
      if (in_array($key, $skip_keys) || empty($setting)) {
        continue;
      }
      $social_name = str_replace('italiagov_socials_', '', $key);
      $icon_class = 'Icon-' . $social_name;
      $items[] = [
        'path' => $setting,
        'social' => ucfirst($social_name),
        'icon_class' => $icon_class,
      ];
    }
    $structure = [
      'title' => $settings['italiagov_socials_title'],
      'items' => $items,
    ];
    return $structure;
  }

  /**
   * Building of export contacts structure.
   *
   * @return array
   *   Keyed array
   */
  public function exportContacts() {
    global $base_url;
    $settings = $this->configFactory->get('dashboard_base.dashgeneralsettings')
      ->get('dash_base_block_contact');
    // Prefix '/contact' path string with the site baseurl
    // to obtain base_url/contact.
    $search = '/contact';
    $body = str_replace(
      $search,
      $base_url . $search,
      $settings['contact_bs_text']
    );
    return [
      'title' => $settings['contact_bs_title'],
      'body' => $body,
    ];
  }

  /**
   * Recursive helper function building the menu structure ready to be exported.
   *
   * As a json resource.
   *
   * @param array $menu
   *   Menu name.
   *
   * @return array
   *   Keyed array
   */
  protected function exportMenuItems(array $menu) {
    $items = [];
    foreach ($menu as $link) {
      $label = $link->link->getTitle();
      $path = $link->link->getUrlObject()->setAbsolute(TRUE)->toString();
      $sub_menu = $link->hasChildren;
      if ($sub_menu) {
        $sub_menu = $this->exportMenuItems($link->subtree);
      }
      $items['items'][] = [
        'label' => $label,
        'path' => $path,
        'sub_menu' => $sub_menu,
      ];
    }
    return $items;
  }

}
