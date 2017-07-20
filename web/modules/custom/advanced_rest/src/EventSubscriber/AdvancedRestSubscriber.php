<?php

namespace Drupal\advanced_rest\EventSubscriber;

use Drupal\advanced_rest\Plugin\AdvancedResourceBase;
use Drupal\advanced_rest\Plugin\AdvancedRestResourcePluginManager;
use Drupal\rest\RequestHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class AdvancedRestSubscriber.
 *
 * @package Drupal\advanced_rest
 */
class AdvancedRestSubscriber implements EventSubscriberInterface {

  /**
   * The REST Plugin Manager.
   *
   * @var \Drupal\rest\Plugin\Type\ResourcePluginManager
   */
  protected $pluginManager;

  /**
   * AdvancedRestSubscriber constructor.
   *
   * @param \Drupal\advanced_rest\Plugin\AdvancedRestResourcePluginManager $plugin_manager
   *   The Plugin Manager.
   */
  public function __construct(AdvancedRestResourcePluginManager $plugin_manager) {
    $this->pluginManager = $plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST] = ['onKernelRequest', 40];
    $events[KernelEvents::CONTROLLER] = ['onKernelController', 40];
    $events[KernelEvents::EXCEPTION] = ['onKernelException', 40];
    return $events;
  }

  /**
   * This method is called whenever the kernel.request event is dispatched.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   *   The Controller event.
   */
  public function onKernelRequest(GetResponseEvent $event) {
    $request = $event->getRequest();
    if (!$request->headers->has('Content-Type')) {
      return;
    }

    $mimeType = $request->headers->get('Content-Type');
    $format = $request->getFormat($mimeType);
    if ($format == 'json') {
      $request->setRequestFormat($format);
    }
  }

  /**
   * This method is called whenever the kernel.controller event is dispatched.
   *
   * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
   *   The Controller event.
   *
   * @throws \Exception
   *   Throws exception expected.
   */
  public function onKernelController(FilterControllerEvent $event) {
    $controller = $event->getController();

    // $controller passed can be either a class or a Closure.
    // If it is a class, it comes in array format.
    if (!is_array($controller)) {
      return;
    }

    $handler = $controller[0];
    if (!($handler instanceof RequestHandler)) {
      return;
    }

    $request = $event->getRequest();
    $resource = $this->getAdvancedRestResource($request);

    if (!$resource) {
      return;
    }

    $error = FALSE;
    try {
      if ($resource->validateParameters() === FALSE) {
        $error = 'Invalid parameters.';
      }
    }
    catch (\Exception $e) {
      $error = $e->getMessage();
    }

    if ($error) {
      throw new BadRequestHttpException($error);
    }
  }

  /**
   * This method is called whenever the kernel.exception event is dispatched.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
   *   The Response for Exception event.
   */
  public function onKernelException(GetResponseForExceptionEvent $event) {
    if ($event->getRequest()->getRequestFormat() != 'json') {
      return;
    }

    $exception = $event->getException();
    $message = $exception->getMessage();
    $code = Response::HTTP_SERVICE_UNAVAILABLE;

    if (method_exists($exception, 'getStatusCode')) {
      $code = $exception->getStatusCode();
    }

    if (empty($message)) {
      $message = $this->getDefaultExceptionMessage($message, $code);
    }

    $data = [
      'status' => 'error',
      'message' => str_replace('"', "'", $message),
      'code' => $code,
      'data' => NULL,
    ];

    $event->setResponse(new JsonResponse($data, $code));
    $event->stopPropagation();
  }

  /**
   * Helper method: get default exception message.
   *
   * @param string $message
   *   The Exception message.
   * @param int $code
   *   The Exception status code.
   *
   * @return string
   *   The default Exception message.
   */
  protected function getDefaultExceptionMessage($message, $code) {
    if (!empty($message)) {
      return $message;
    }

    if ($code == Response::HTTP_FORBIDDEN) {
      $message = 'Access denied to this Resource';
    }

    return $message;
  }

  /**
   * Get AdvancedResourceBase from Request.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The Request object.
   *
   * @return \Drupal\advanced_rest\Plugin\AdvancedResourceBase|false
   *   An instance of AdvancedResourceBase or FALSE if it does not exists.
   */
  protected function getAdvancedRestResource(Request $request) {
    if (!$request->attributes->has('_rest_resource_config')) {
      return FALSE;
    }
    $plugin_id = $request->attributes->get('_rest_resource_config');
    $resource = $this->pluginManager->createInstance($plugin_id);
    return ($resource instanceof AdvancedResourceBase) ? $resource : FALSE;
  }

}
