<?php

namespace Drupal\references_modal\Plugin\Field\FieldWidget;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\EntityReferenceAutocompleteWidget;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'references_modal_widget' widget.
 *
 * @FieldWidget(
 *   id = "references_modal_widget",
 *   label = @Translation("References Modal"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class ReferencesModalWidget extends EntityReferenceAutocompleteWidget implements ContainerFactoryPluginInterface {

  /**
   * The Entity Type Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    $plugin_id,
    $plugin_definition,
    FieldDefinitionInterface $field_definition,
    array $settings,
    array $third_party_settings,
    EntityTypeManagerInterface $entity_type_manager
  ) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    $element['target_id']['#weight'] = 0;
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $form['#attached']['library'][] = 'references_modal/form';
    $element['references_modal'] = [
      '#type' => 'container',
    ];

    if (!empty($element['target_id']['#default_value'])) {
      $entity = $element['target_id']['#default_value'];
      $access = $entity->access('create');

      $title = $this->t('Edit');
      $entity_type = $entity->getEntityTypeId();
      $bundle = $entity->bundle();
      $id = $entity->id();
      $link = $this->generateLink($title, $entity_type, $bundle, $id);

      $element['references_modal']['edit_reference'] = $link;
      $element['references_modal']['edit_reference']['#access'] = $access;
      return $element;
    }

    $entity_type = $element['target_id']['#target_type'];
    $id = 0;
    $bundles = $element['target_id']['#selection_settings']['target_bundles'];
    $entity_definitions = $this->entityTypeManager->getDefinitions();
    $definition = $entity_definitions[$entity_type];

    foreach ($bundles as $bundle) {
      $title = $this->t('Create @bundle', [
        '@bundle' => $this->getBundleLabel($definition, $bundle),
      ]);
      $link = $this->generateLink($title, $entity_type, $bundle, $id);

      $element_name = 'create_reference_' . $bundle;
      $element['references_modal'][$element_name] = $link;

      $access = $this->entityTypeManager->getAccessControlHandler($entity_type)
        ->createAccess($bundle);
      $element['references_modal'][$element_name]['#access'] = $access;
    }

    return $element;
  }

  /**
   * Generates a render array for a link to be used inside the modal form.
   *
   * @param string $title
   *   The link title.
   * @param string $entity_type
   *   The entity type.
   * @param string $bundle
   *   The entity bundle.
   * @param int $id
   *   The entity id. Use 0 to create a new entity.
   *
   * @return array
   *   A render array of the link.
   */
  protected function generateLink($title, $entity_type, $bundle, $id) {
    return [
      '#type' => 'link',
      '#title' => $title,
      '#url' => Url::fromRoute('references_modal.open_modal_form', [
        'entity_type' => $entity_type,
        'bundle' => $bundle,
        'id' => $id,
      ]),
      '#attributes' => [
        'class' => [
          'use-ajax',
          'button',
        ],
      ],
      '#weight' => 1,
    ];
  }

  /**
   * Get bundle label.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $definition
   *   The entity type definition.
   * @param string $bundle
   *   The bundle name.
   *
   * @return string
   *   The bundle label.
   */
  protected function getBundleLabel(EntityTypeInterface $definition, $bundle) {
    return $this->entityTypeManager
      ->getStorage($definition->getBundleEntityType())
      ->load($bundle)
      ->label();
  }

}
