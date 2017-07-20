<?php

namespace Drupal\dashboard_base\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller routines for user routes.
 */
class DashboardBaseController extends ControllerBase {

  /**
   * Redirecting of visitors accordingly to their registered or anonymous state.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   Redirect response.
   */
  public function dashboardBasePage() {
    if ($this->currentUser()->isAnonymous()) {
      return $this->redirect('<front>');
    }
    return $this->redirect(
      'entity.user.canonical',
      ['user' => $this->currentUser()->id()]
    );
  }

}
