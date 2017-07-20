<?php

namespace Drupal\advanced_rest\Plugin;

use Drupal\advanced_rest\AdvancedRestEntityDecoratorFactoryInterface;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for Rest Entity Decorator plugins.
 */
abstract class RestEntityDecoratorBase extends PluginBase implements AdvancedRestEntityDecoratorInterface, ContainerFactoryPluginInterface {

  /**
   * The Entity Type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * An content entity instance.
   *
   * @var \Drupal\Core\Entity\ContentEntityBase
   */
  protected $entity;

  /**
   * Array of values extracted from the entity.
   *
   * @var array
   */
  public $values;

  /**
   * The Rest Entity decorator factory.
   *
   * @var \Drupal\advanced_rest\AdvancedRestEntityDecoratorFactoryInterface
   */
  protected $entityDecoratorFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    EntityTypeManagerInterface $entity_type_manager,
    AdvancedRestEntityDecoratorFactoryInterface $entity_decorator_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entity_type_manager;
    $this->entityDecoratorFactory = $entity_decorator_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('advanced_rest.entity_decorator_factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setEntity(ContentEntityBase $entity) {
    $entity_type = $entity->getEntityTypeId();
    $allowed_types = [$entity_type, 'entity'];

    if (!in_array($this->pluginDefinition['entityType'], $allowed_types)) {
      $message = sprintf('Invalid entity type supplied for entity decorator: %s', $entity_type);
      throw new \InvalidArgumentException($message);
    }

    $this->entity = $entity;
    $this->extractValues();
  }

  /**
   * {@inheritdoc}
   */
  public function getEntity() {
    if (!isset($this->entity)) {
      $message = sprintf('Missing source entity for decorator %s', __CLASS__);
      throw new \RuntimeException($message);
    }
    return $this->entity;
  }

  /**
   * Extract values from the array.
   */
  protected function extractValues() {
    $values = $this->entity->toArray();
    if (empty($values)) {
      return;
    }
    foreach ($values as $key => $value) {
      foreach ($value as $i => $v) {
        if (is_array($v) && (count($v) == 1)) {
          $value[$i] = reset($v);
        }
      }

      if (is_array($value) && (count($value) == 1)) {
        $value = reset($value);
      }
      $values[$key] = !empty($value) ? $value : NULL;
    }

    $this->values = $values;
  }

  /**
   * {@inheritdoc}
   */
  public function get($key, $index = NULL) {
    if (!array_key_exists($key, $this->values)) {
      $message = sprintf('Invalid entity property provided: %s', $key);
      throw new \InvalidArgumentException($message);
    }

    $value = $this->values[$key];
    if (!is_null($index) && is_array($value) && array_key_exists($index, $value)) {
      $value = $value[$index];
    }
    return !empty($value) ? $value : "";
  }

  /**
   * {@inheritdoc}
   */
  public function getDecoratedReferencedEntities($field_name, $index = NULL) {
    $entity = $this->getEntity();
    if (!$entity->hasField($field_name)) {
      $format = 'Unknown field %s for entity %s of bundle %s';
      $type = $entity->getEntityTypeId();
      $bundle = $entity->bundle();
      $message = sprintf($format, $field_name, $type, $bundle);
      throw new \InvalidArgumentException($message);
    }

    $field = $entity->get($field_name);
    $field_type = $field->getItemDefinition()->getFieldDefinition()->getType();
    $allowed_types = ['entity_reference', 'entity_reference_revisions'];
    if (!in_array($field_type, $allowed_types)) {
      $message = sprintf('Field %s is not an entity reference field', $field_name);
      throw new \InvalidArgumentException($message);
    }

    $entities = $field->referencedEntities();
    $decorator = $this->entityDecoratorFactory;
    foreach ($entities as $i => $referenced_entity) {
      $entities[$i] = $decorator->get($referenced_entity)->toArray();
    }

    return (!is_null($index) && isset($entities[$index])) ? $entities[$index] : $entities;
  }

}
