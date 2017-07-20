<?php

/**
 * @file
 * Adds custom steps implementation.
 */

use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\AfterStepScope;
use Behat\Testwork\Tester\Result\TestResult;
use Behat\Mink\Driver\GoutteDriver;

define('DASH_CONTACT_SELECT_NOT_SHOW_INTEREST', 1);
define('DASH_CONTACT_SELECT_SHOW_INTEREST', 2);


/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawDrupalContext implements SnippetAcceptingContext {

  /**
   * Initializes context.
   *
   * Every scenario gets its own context instance.
   * You can also pass arbitrary arguments to the
   * context constructor through behat.yml.
   */
  public function __construct() {
  }

  /**
   * @Given I log in as :arg1
   */
  public function iLogInAs($username) {
    $base_url = $this->getMinkParameter('base_url');
    $login_link = $this->getDriver('drush')->drush('uli', array(
      "'${username}'",
      '--browser=0',
      "--uri=${base_url}",
    ));
    $login_link = trim($login_link);
    $this->getSession()->visit($login_link);
    if (!$this->loggedIn()) {
      throw new Exception('Unable to login as user ' . $username);
    }
    $this->user = user_load_by_name($username);
  }

  /**
   * @AfterStep
   */
  public function takeScreenShotAfterFailedStep(AfterStepScope $scope) {
    if (TestResult::FAILED === $scope->getTestResult()->getResultCode()) {
      $driver = $this->getSession()->getDriver();
      if (!($driver instanceof GoutteDriver)) {
        return;
      }
      $path = getenv('BEHAT_SCREENSHOT_DEBUG') ? getenv('BEHAT_SCREENSHOT_DEBUG') : '.';
      $steptext = $scope->getStep()->getText();
      $filename = preg_replace('#[^a-zA-Z0-9\._-]#', '', $steptext) . '.html';
      $content = $this->getSession()->getDriver()->getContent();
      file_put_contents($path . '/' . $filename, $content);
    }
  }

  /**
   * @BeforeScenario @email
   */
  public function beforeEmailScenario() {
    $this->mailConfig = \Drupal::configFactory()->getEditable('system.mail');
    $this->savedMailDefaults = $this->mailConfig->get('interface.default');
    $this->mailConfig->set('interface.default', 'test_mail_collector')->save();
    \Drupal::state()->set('system.test_mail_collector', array());
  }

  /**
   * @AfterScenario @email
   */
  public function afterEmailScenario() {
    $this->mailConfig->set('interface.default', $this->savedMailDefaults)->save();
  }

  /**
   * Helper to get the last email.
   *
   * @todo this method does a cache reset each time, optimize this.
   */
  public function getLastEmail() {
    // Reset state cache.
    \Drupal::state()->resetCache();
    $mails = \Drupal::state()->get('system.test_mail_collector') ?: array();
    $last_mail = end($mails);
    if (!$last_mail) {
      throw new Exception('No mail was sent.');
    }
    return $last_mail;
  }

  /**
   * @Then the last email sent should have recipient :recipient
   */
  public function theLastMailSentShouldHaveRecipient($recipient) {
    $last_mail = $this->getLastEmail();
    if ($last_mail['to'] != $recipient) {
      throw new \Exception("Recpient mismatch: " . $last_mail['to'] . " | " . $recipient);
    }
    $this->lastMail = $last_mail;
  }

  /**
   * @Then the last email sent should have subject :subject
   */
  public function theLastMailSentShouldHaveSubject($subject) {
    $last_mail = $this->getLastEmail();
    if ($last_mail['subject'] != $subject) {
      throw new \Exception("Subject mismatch:" . $last_mail['subject'] . " | " . $subject);
    }
    $this->lastMail = $last_mail;
  }

  /**
   * @Then the last email sent should have a body containing :text
   */
  public function theLastMailSentShouldHaveBodyContainint($text) {
    $last_mail = $this->getLastEmail();
    if (strpos($last_mail['body'], $text) === FALSE) {
      throw new \Exception("Body text not found:" . $last_mail['body'] . " | " . $text);
    }
    $this->lastMail = $last_mail;
  }

  /**
   * @Then no email should have been sent
   */
  public function noEmailShouldBeSent() {
    // Reset state cache.
    \Drupal::state()->resetCache();
    $mails = \Drupal::state()->get('system.test_mail_collector') ?: array();
    if (!empty($mails)) {
      throw new Exception('Emails were sent/');
    }
  }

  /**
   * @Then check dashboard contact form interest area select
   */
  public function checkDashboardContactFormInterestAreaSelect() {
    $config = \Drupal::config('dashboard_contact.contactformconfig');
    $config_specific = $config->get('dashboard_contact.contact_select_type');

    $element = '.contact-form--NoSelect';
    $text_shown = 'Configuration Dashboard form settings: Do not show the select';
    if ($config_specific == DASH_CONTACT_SELECT_SHOW_INTEREST) {
      $element = '#edit-select-projects';
      $text_shown = 'Configuration Dashboard form settings: Show the select';
    }
    $this->assertSession()->elementExists('css', $element);
    echo $text_shown;
  }

}
