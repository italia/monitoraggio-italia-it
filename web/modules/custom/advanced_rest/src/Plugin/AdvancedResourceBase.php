<?php

namespace Drupal\advanced_rest\Plugin;

use Drupal\advanced_rest\AdvancedRestEntityDecoratorFactoryInterface;
use Drupal\advanced_rest\ResourceDescriptionHandlerInterface;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheableDependencyInterface;
use Drupal\Core\Cache\CacheableResponseTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\TypedData\TypedDataManagerInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\RouteCollection;

/**
 * Base class for Advanced rest resource plugins.
 */
abstract class AdvancedResourceBase extends ResourceBase implements AdvancedResourceInterface, CacheableDependencyInterface {

  use CacheableResponseTrait;

  /**
   * Cache Max Age for a rest response.
   */
  const CACHE_MAX_AGE = Cache::PERMANENT;

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * The Request object.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * The current Request method.
   *
   * @var string
   */
  protected $method;

  /**
   * The Command response data.
   *
   * @var array
   */
  protected $response;

  /**
   * An array of parameters to be used by the Resource.
   *
   * @var array
   */
  protected $parameters;

  /**
   * An array describing resource parameters.
   *
   * @var array
   */
  protected $resourceDescription;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The Rest Resource Description Handler.
   *
   * @var \Drupal\advanced_rest\ResourceDescriptionHandlerInterface
   */
  protected $descriptionHandler;

  /**
   * The Rest Entity decorator factory.
   *
   * @var \Drupal\advanced_rest\AdvancedRestEntityDecoratorFactoryInterface
   */
  protected $entityDecoratorFactory;

  /**
   * The Typed Data Manager.
   *
   * @var \Drupal\Core\TypedData\TypedDataManagerInterface
   */
  protected $typedDataManager;

  /**
   * Constructs a Drupal\advanced_rest\Plugin\AdvancedResourceBase object.
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
    TypedDataManagerInterface $typed_data_manager) {

    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);

    $this->currentUser = $current_user;
    $this->request = $request_stack->getCurrentRequest();
    $this->method = $this->request->getMethod();
    $this->entityTypeManager = $entity_type_manager;
    $this->descriptionHandler = $description_handler;
    $this->entityDecoratorFactory = $entity_decorator_factory;
    $this->typedDataManager = $typed_data_manager;

    $this->loadResourceDescription();
    $this->setParametersFromRequest($this->request);
    $this->filterParameters();
    $this->setCacheMetadataFromPathParameters();
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
      $container->get('typed_data_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getRequest() {
    return $this->request;
  }

  /**
   * {@inheritdoc}
   */
  public function getMethod() {
    return $this->method;
  }

  /**
   * {@inheritdoc}
   */
  public function routes() {
    $routes = $this->getRoutes();
    $resource = $this->getResourceDescription();

    if (!isset($resource['descriptions'])) {
      return $routes;
    }

    foreach ($resource['descriptions'] as $method => $description) {
      if (empty($description['endpoint'])) {
        continue;
      }
      $endpoint = $description['endpoint'];
      $id_keys = [
        $resource['id'],
        $method,
        'json',
      ];
      $id = implode('.', $id_keys);

      // Add route options defined into the resource description file.
      if (!empty($endpoint['options'])) {
        $routes->get($id)->addOptions($endpoint['options']);
      }

      // Add route requirements defined into the resource description file.
      if (!empty($endpoint['requirements'])) {
        $routes->get($id)->addRequirements($endpoint['requirements']);
      }
    }

    return $routes;
  }

  /**
   * Set resource parameters.
   *
   * @param array $parameters
   *   An array of parameters.
   */
  public function setParameters(array $parameters = []) {
    $this->parameters = [];
    foreach ($this->getResourceParameters() as $param) {
      if (isset($parameters[$param])) {
        $this->setParameter($param, $parameters[$param]);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function setParametersFromRequest(Request $request) {
    $this->parameters = [];
    $content = $request->getContent();
    $methods = ['POST', 'PUT', 'DELETE'];
    $allowed_format = ($request->getRequestFormat() == 'json');

    if (in_array($request->getMethod(), $methods) && !empty($content) && $allowed_format) {
      $data = json_decode($content, TRUE);
      $parameters = is_array($data) ? $data : [];
      $request->request->add($parameters);
    }

    foreach ($this->getResourceParameters() as $param) {
      $this->setParameter($param, $request->get($param));
    }
  }

  /**
   * Set a parameter value.
   *
   * @param string $param
   *   The parameter key.
   * @param mixed $value
   *   The parameter value.
   *
   * @throws \InvalidArgumentException
   *   Throws exception expected.
   */
  public function setParameter($param, $value) {
    if (!is_string($param)) {
      throw new \InvalidArgumentException('The parameter name must be a string');
    }
    if ($value != NULL) {
      $this->parameters[$param] = $value;
    }
  }

  /**
   * Unset a parameter value.
   *
   * @param string $param
   *   The parameter key.
   *
   * @throws \InvalidArgumentException
   *   Throws exception expected.
   */
  public function unsetParameter($param) {
    if (!is_string($param)) {
      throw new \InvalidArgumentException('The parameter name must be a string');
    }
    if (isset($this->parameters[$param])) {
      unset($this->parameters[$param]);
    }
  }

  /**
   * Automatically extracts parameters from resource descriptions, if defined.
   *
   * @return array
   *   An array with the request parameters.
   */
  public function getResourceParameters() {
    $this->loadResourceDescription();
    $description = $this->getResourceDescription($this->getMethod());
    return !empty($description['params']) ? array_keys($description['params']) : [];
  }

  /**
   * {@inheritdoc}
   */
  public function loadResourceDescription() {
    if (!empty($this->resourceDescription)) {
      return $this->resourceDescription;
    }

    $handler = $this->descriptionHandler;
    $definition = $this->getPluginDefinition();
    if (!$handler->moduleProvidesDescriptions($definition['provider'])) {
      return NULL;
    }

    $id = $definition['provider'] . '.' . $definition['id'];
    $this->resourceDescription = $handler->loadDescription($id);
    return $this->resourceDescription;
  }

  /**
   * Get Resource Description.
   *
   * @param string|null $method
   *   (optional) The resource method.
   *
   * @return array
   *   The resource description array.
   */
  public function getResourceDescription($method = NULL) {
    $resource = $this->resourceDescription;
    return (!empty($method) && isset($resource['descriptions'][$method])) ? $resource['descriptions'][$method] : $resource;
  }

  /**
   * Get command parameters.
   *
   * @return array
   *   An array of command parameters.
   */
  public function getParameters() {
    return $this->parameters;
  }

  /**
   * Get command parameter.
   *
   * @param string $param
   *   The parameter key.
   *
   * @return mixed
   *   The parameter value if exists or NULL.
   */
  public function getParameter($param) {
    return isset($this->parameters[$param]) ? $this->parameters[$param] : NULL;
  }

  /**
   * {@inheritdoc}
   */
  public function filterParameters() {
    $resourceDescription = $this->getResourceDescription($this->getMethod());
    if (empty($resourceDescription['params'])) {
      return;
    }

    foreach ($resourceDescription['params'] as $name => $param) {
      $this->filter($name);
    }
  }

  /**
   * Filter a parameter updating its value if necessary.
   *
   * @param string $name
   *   The parameter name.
   *
   * @throws \InvalidArgumentException
   *   Throws exception expected.
   */
  protected function filter($name) {
    $value = $this->getParameter($name);
    if (empty($value)) {
      return;
    }

    $resourceDescription = $this->getResourceDescription($this->getMethod());
    if (empty($resourceDescription['params'][$name]['filters'])) {
      return;
    }

    $filters = $resourceDescription['params'][$name]['filters'];
    foreach ($filters as $filter) {
      if (empty($filter['method'])) {
        $message = 'A "method" value must be specified for each complex filter';
        throw new \InvalidArgumentException($message);
      }

      $args = $this->getFilterArguments($filter, $value);
      $value = call_user_func_array($filter['method'], $args);
      $this->setParameter($name, $value);
    }
  }

  /**
   * Get filter arguments.
   *
   * @param string $filter
   *   The parameter name.
   * @param mixed $value
   *   The parameter value.
   *
   * @return array
   *   An array of filter arguments extracted from resource description.
   *
   * @throws \InvalidArgumentException
   *   Throws exception expected.
   */
  protected function getFilterArguments($filter, $value) {
    if (empty($filter['args'])) {
      $message = 'An "args" value must be specified for each complex filter';
      throw new \InvalidArgumentException($message);
    }

    $args = [];
    foreach ($filter['args'] as $arg) {
      if ($arg == '@value') {
        $arg = $value;
      }
      elseif ($arg == '@api') {
        $arg = $this;
      }
      $args[] = $arg;
    }
    return $args;
  }

  /**
   * {@inheritdoc}
   */
  public function validateParameters() {
    // Skip validation if no resource description has been defined.
    $resourceDescription = $this->getResourceDescription($this->getMethod());
    if (empty($resourceDescription['params'])) {
      return TRUE;
    }

    $params = $resourceDescription['params'];
    $resource_params = $this->getParameters();
    $typed_data = $this->typedDataManager;

    foreach ($params as $name => $param) {
      // Check for missing required parameters.
      if (!empty($param['required']) && empty($resource_params[$name])) {
        $message = $this->t('Missing required parameter !name', [
          '!name' => $name,
        ]);
        throw new HttpException(404, $message);
      }

      // Skip validation for missing optional parameters.
      if (!isset($resource_params[$name]) || ($resource_params[$name] == NULL) || empty($param['type'])) {
        continue;
      }

      // Create data definition and data validator.
      $definition = $typed_data->createDataDefinition($param['type']);
      if (isset($param['constraints'])) {
        foreach ($param['constraints'] as $constraint) {
          $definition->addConstraint($constraint['name'], $constraint['options']);
        }
      }
      $validator = $typed_data->create($definition);

      // Put value into an array to avoid redundant logic.
      $value = $resource_params[$name];
      $values = (!is_array($value)) ? [$value] : $value;

      foreach ($values as $value) {
        $validator->setValue($value);
        $errors = $validator->validate();
        if ($errors->count() == 0) {
          continue;
        }

        // One error message is enough.
        $message = $this->t('@parameter: @message', [
          '@parameter' => $name,
          '@message' => $errors->get(0)->getMessageTemplate(),
        ]);
        throw new HttpException(404, $message);
      }
    }

    return TRUE;
  }

  /**
   * Custom implementation of parent::getRoutes().
   *
   * Manage json format for POST and PUT verbs.
   *
   * @return \Symfony\Component\Routing\RouteCollection
   *   Routecollection object.
   */
  protected function getRoutes() {
    $collection = new RouteCollection();

    $definition = $this->getPluginDefinition();
    $canonical_path = isset($definition['uri_paths']['canonical']) ? $definition['uri_paths']['canonical'] : '/' . strtr($this->pluginId, ':', '/') . '/{id}';
    $create_path = isset($definition['uri_paths']['https://www.drupal.org/link-relations/create']) ? $definition['uri_paths']['https://www.drupal.org/link-relations/create'] : '/' . strtr($this->pluginId, ':', '/');

    $route_name = strtr($this->pluginId, ':', '.');

    $methods = $this->availableMethods();
    foreach ($methods as $method) {
      $route = $this->getBaseRoute($canonical_path, $method);
      switch ($method) {
        case 'PUT':
        case 'POST':
          $route->setPath($create_path);
          // Restrict the incoming HTTP Content-type header to the known
          // serialization formats.
          foreach ($this->serializerFormats as $format_name) {
            $format_route = clone $route;
            $format_route->addRequirements(['_content_type_format' => $format_name]);
            $collection->add("$route_name.$method.$format_name", $format_route);
          }
          break;

        case 'PATCH':
          // Restrict the incoming HTTP Content-type header to the known
          // serialization formats.
          $route->addRequirements(['_content_type_format' => implode('|', $this->serializerFormats)]);
          $collection->add("$route_name.$method", $route);
          break;

        case 'GET':
        case 'HEAD':
        case 'DELETE':
          // Restrict GET and HEAD requests to the media type specified in the
          // HTTP Accept headers.
          foreach ($this->serializerFormats as $format_name) {
            // Expose one route per available format.
            $format_route = clone $route;
            $format_route->addRequirements(['_format' => $format_name]);
            $collection->add("$route_name.$method.$format_name", $format_route);
          }
          break;

        default:
          $collection->add("$route_name.$method", $route);
          break;
      }
    }

    return $collection;
  }

  /**
   * {@inheritdoc}
   */
  public function buildResponse($data = NULL, $status = 200, array $headers = ['Content-Type' => 'application/json']) {
    $response = new ResourceResponse($data, $status, $headers);
    $response->addCacheableDependency($this);
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    $contexts = $this->getCacheableMetadata()->getCacheContexts();
    return $contexts + ['url'];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return $this->getCacheableMetadata()->getCacheTags();
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    $method = $this->getMethod();
    return in_array($method, ['GET', 'HEAD']) ? $this::CACHE_MAX_AGE : 0;
  }

  /**
   * {@inheritdoc}
   */
  public function setCacheMetadataFromPathParameters() {
    $description = $this->getResourceDescription($this->getMethod());
    if (empty($description['endpoint']['options']['parameters'])) {
      return;
    }
    $path_parameters = array_keys($description['endpoint']['options']['parameters']);
    foreach ($path_parameters as $path_parameter) {
      $parameter = $this->request->get($path_parameter);
      $this->addCacheableDependency($parameter);
    }
  }

}
