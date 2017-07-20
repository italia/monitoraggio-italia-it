<?php

namespace Drupal\dashboard_base\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Logger\LoggerChannelFactory;

/**
 * Provides a 'ContactBlock' block.
 *
 * @Block(
 *  id = "contact_block",
 *  admin_label = @Translation("Contact block"),
 * )
 */
class ContactBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * A logger instance.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, LoggerChannelFactory $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('logger.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {

    $build = [];

    // Load configuration.
    $config = $this->configFactory->getEditable('dashboard_base.dashgeneralsettings');
    if (empty($config)) {
      drupal_set_message($this->t('There was a problem loading this page. Please contact administrators.'), 'error');
      // Logs error.
      $this->logger->get('dashboard_base')->error('dashboard_base.dashgeneralsettings: configuration empty.');
      return;
    }

    // Load text block.
    if (empty($config->get('dash_base_block_contact.contact_bs_text'))) {
      $build['contact_block']['#markup'] = '';
    }
    else {
      $build['contact_block']['#markup'] = $config->get('dash_base_block_contact.contact_bs_text');
    }

    // Load title block.
    if (empty($config->get('dash_base_block_contact.contact_bs_title'))) {
      $build['contact_block']['#title'] = $this->t('Contact');
    }
    else {
      $build['contact_block']['#title'] = $config->get('dash_base_block_contact.contact_bs_title');
    }

    return $build;
  }

}
