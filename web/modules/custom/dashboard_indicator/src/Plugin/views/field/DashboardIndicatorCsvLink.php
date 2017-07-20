<?php

namespace Drupal\dashboard_indicator\Plugin\views\field;

use Drupal\advanced_rest\AdvancedRestEntityDecoratorFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Url;
use Drupal\views\Plugin\views\display\DisplayPluginBase;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;
use Drupal\views\ViewExecutable;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Field handler to present the link to the CSV of the Indicator.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("indicator_csv_link")
 */
class DashboardIndicatorCsvLink extends FieldPluginBase {

  /**
   * The Entity Decorator Factory.
   *
   * @var \Drupal\advanced_rest\AdvancedRestEntityDecoratorFactoryInterface
   */
  protected $decorator;

  /**
   * The Entity Type Manger Service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * The Language Manager Service.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    AdvancedRestEntityDecoratorFactoryInterface $decorator,
    EntityTypeManagerInterface $entity_manager,
    LanguageManagerInterface $language_manager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->decorator = $decorator;
    $this->entityManager = $entity_manager;
    $this->languageManager = $language_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('advanced_rest.entity_decorator_factory'),
      $container->get('entity_type.manager'),
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function init(ViewExecutable $view, DisplayPluginBase $display, array &$options = NULL) {
    parent::init($view, $display, $options);

    // Don't add the additional fields to groupby.
    if (!empty($this->options['link_to_indicator_csv'])) {
      $this->additional_fields['indicator_id'] = [
        'table' => 'dashboard_indicator',
        'field' => 'id',
      ];
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['alter']['alter_text'] = ['default' => TRUE];
    $options['csv_indicator_link_title'] = [
      'default' => isset($this->definition['csv_indicator_link_title default']) ? $this->definition['csv_indicator_link_title default'] : 'CSV',
    ];
    $options['link_to_indicator_csv'] = [
      'default' => isset($this->definition['link_to_indicator_csv default']) ? $this->definition['link_to_indicator_csv default'] : FALSE,
    ];
    return $options;
  }

  /**
   * Provide link to node option.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $options = $this->options;
    $form['csv_indicator_link_title'] = [
      '#title' => $this->t('Link title'),
      '#type' => 'textfield',
      '#default_value' => !empty($options['csv_indicator_link_title']) ? $options['csv_indicator_link_title'] : 'CSV',
    ];

    $form['link_to_indicator_csv'] = [
      '#title' => $this->t('Link this field to the CSV of the Indicator'),
      '#description' => $this->t("Enable to override this field's links."),
      '#type' => 'checkbox',
      '#default_value' => !empty($options['link_to_indicator_csv']),
    ];

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * Prepares link to the CSV of the Indicator.
   *
   * @param string $data
   *   The XSS safe string for the link text.
   * @param \Drupal\views\ResultRow $values
   *   The values retrieved from a single row of a view's query result.
   *
   * @return string
   *   Returns a string for the link text.
   */
  protected function renderLink($data, ResultRow $values) {
    if (empty($this->options['link_to_indicator_csv']) || empty($this->additional_fields['indicator_id'])) {
      return $data;
    }

    if ($data === NULL || $data === '') {
      $this->options['alter']['make_link'] = FALSE;
      return $data;
    }

    $indicator_id = $this->getValue($values, 'indicator_id');
    $storage = $this->entityManager->getStorage('dashboard_indicator');
    $indicator = $storage->load($indicator_id);
    $indicator_data = $this->decorator->get($indicator)->toArray();

    // Hide CSV link if no dataset has been found.
    if (empty($indicator_data['datasets'])) {
      return NULL;
    }

    $url = Url::fromRoute('dashboard_indicator.download_csv', [
      'indicator' => $indicator_data['key'],
    ]);

    $this->options['alter']['url'] = $url;
    $this->options['alter']['make_link'] = TRUE;

    if (!empty($this->options['csv_indicator_link_title'])) {
      $this->options['alter']['alter_text'] = TRUE;
      $this->options['alter']['text'] = $this->options['csv_indicator_link_title'];
    }

    if (!empty($this->options['element_class'])) {
      $this->options['alter']['link_class'] = $this->options['element_class'];
    }

    if (isset($this->aliases['langcode'])) {
      $languages = $this->languageManager->getLanguages();
      $langcode = $this->getValue($values, 'langcode');
      if (isset($languages[$langcode])) {
        $this->options['alter']['language'] = $languages[$langcode];
      }
      else {
        unset($this->options['alter']['language']);
      }
    }
    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $value = $this->getValue($values);
    return $this->renderLink($this->sanitizeValue($value), $values);
  }

}
