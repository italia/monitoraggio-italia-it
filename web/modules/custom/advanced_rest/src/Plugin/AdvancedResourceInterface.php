<?php

namespace Drupal\advanced_rest\Plugin;

use Drupal\rest\Plugin\ResourceInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines an interface for Advanced rest resource plugins.
 */
interface AdvancedResourceInterface extends ResourceInterface {

  /**
   * Get the request.
   *
   * @return \Symfony\Component\HttpFoundation\Request|null
   *   The request.
   */
  public function getRequest();

  /**
   * Get Request method.
   *
   * @return string
   *   The current Request method.
   */
  public function getMethod();

  /**
   * Load Resource Description if exists.
   *
   * @return array|null
   *   An array containing the resource description or NULL if not exists.
   */
  public function loadResourceDescription();

  /**
   * Set resource parameters from Request.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The REST Resource HTTP Request.
   */
  public function setParametersFromRequest(Request $request);

  /**
   * Filter provided parameters.
   */
  public function filterParameters();

  /**
   * Set Cache metadata from path parameters.
   */
  public function setCacheMetadataFromPathParameters();

  /**
   * Checks for invalid parameters.
   *
   * Here we add our custom validation to the parameters for this resource.
   *
   * @return bool
   *   TRUE or FALSE the specified parameters are valid or not.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */
  public function validateParameters();

  /**
   * Build Resource Response.
   *
   * @param mixed $data
   *   Response data that should be serialized.
   * @param int $status
   *   The response status code.
   * @param array $headers
   *   An array of response headers.
   *
   * @return \Drupal\rest\ResourceResponse
   *   A ResourceResponse object.
   */
  public function buildResponse($data = NULL, $status = 200, array $headers = ['Content-Type' => 'application/json']);

}
