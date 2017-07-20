<?php

namespace Drupal\dashboard_indicator\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\dashboard_indicator\DashboardIndicatorInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Dashboard indicator entity.
 *
 * @ingroup dashboard_indicator
 *
 * @ContentEntityType(
 *   id = "dashboard_indicator",
 *   label = @Translation("Dashboard indicator"),
 *   bundle_label = @Translation("Dashboard indicator type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\dashboard_indicator\DashboardIndicatorListBuilder",
 *     "views_data" = "Drupal\dashboard_indicator\Entity\DashboardIndicatorViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\dashboard_indicator\Form\DashboardIndicatorForm",
 *       "add" = "Drupal\dashboard_indicator\Form\DashboardIndicatorForm",
 *       "edit" = "Drupal\dashboard_indicator\Form\DashboardIndicatorForm",
 *       "delete" = "Drupal\dashboard_indicator\Form\DashboardIndicatorDeleteForm",
 *     },
 *     "access" = "Drupal\dashboard_indicator\DashboardIndicatorAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\dashboard_indicator\DashboardIndicatorHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "dashboard_indicator",
 *   admin_permission = "administer dashboard indicator entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "key_unique" = "key_unique",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/dashboard_indicator/{dashboard_indicator}",
 *     "add-form" = "/admin/structure/dashboard_indicator/add/{dashboard_indicator_type}",
 *     "edit-form" = "/admin/structure/dashboard_indicator/{dashboard_indicator}/edit",
 *     "delete-form" = "/admin/structure/dashboard_indicator/{dashboard_indicator}/delete",
 *     "collection" = "/admin/structure/dashboard_indicator",
 *   },
 *   bundle_entity_type = "dashboard_indicator_type",
 *   field_ui_base_route = "entity.dashboard_indicator_type.edit_form"
 * )
 */
class DashboardIndicator extends ContentEntityBase implements DashboardIndicatorInterface {
  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return $this->bundle();
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * Gets the Dashboard indicator key_unique.
   *
   * @return string
   *   Key of the Dashboard indicator.
   */
  public function getKeyUnique() {
    return $this->get('key_unique')->value;
  }

  /**
   * Sets the Dashboard indicator key_unique.
   *
   * @param string $key_unique
   *   The Dashboard indicator key.
   *
   * @return \Drupal\dashboard_indicator\DashboardIndicatorInterface
   *   The called Dashboard indicator entity.
   */
  public function setKeyUnique($key_unique) {
    $this->set('key_unique', $key_unique);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? NODE_PUBLISHED : NODE_NOT_PUBLISHED);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Dashboard indicator entity.'))
      ->setReadOnly(TRUE);
    $fields['type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Type'))
      ->setDescription(t('The Dashboard indicator type/bundle.'))
      ->setSetting('target_type', 'dashboard_indicator_type')
      ->setRequired(TRUE);
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Dashboard indicator entity.'))
      ->setReadOnly(TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Dashboard indicator entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\node\Entity\Node::getCurrentUserId')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Dashboard indicator entity.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['key_unique'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Key'))
      ->setDescription(t('The unique key of the Dashboard indicator entity.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE)
      ->addConstraint('UniqueField');

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Dashboard indicator is published.'))
      ->setDefaultValue(TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code for the Dashboard indicator entity.'))
      ->setDisplayOptions('form', array(
        'type' => 'language_select',
        'weight' => 10,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
