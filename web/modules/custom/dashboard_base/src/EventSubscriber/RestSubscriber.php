<?php

namespace Drupal\dashboard_base\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class RestSubscriber.
 *
 * @package Drupal\dashboard_base
 */
class RestSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    // We need to stay at the very end of the queue.
    $events[KernelEvents::RESPONSE] = ['onKernelResponse', -900];
    return $events;
  }

  /**
   * This method is called whenever the kernel.response event is dispatched.
   *
   * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
   *   The response event.
   */
  public function onKernelResponse(FilterResponseEvent $event) {
    if (!$event->isMasterRequest()) {
      return;
    }

    $response = $event->getResponse();
    if ($response->headers->has('x-generator')) {
      $response->headers->remove('x-generator');
    }
  }

}
