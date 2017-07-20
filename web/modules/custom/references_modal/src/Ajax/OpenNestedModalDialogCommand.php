<?php

namespace Drupal\references_modal\Ajax;

use Drupal\Core\Ajax\OpenDialogCommand;

/**
 * Defines an AJAX command to open nested dialogs.
 *
 * @ingroup ajax
 */
class OpenNestedModalDialogCommand extends OpenDialogCommand {

  /**
   * {@inheritdoc}
   */
  public function __construct($selector, $title, $content, array $dialog_options = [], $settings = NULL) {
    $dialog_options['modal'] = TRUE;
    parent::__construct($selector, $title, $content, $dialog_options, $settings);
  }

}
