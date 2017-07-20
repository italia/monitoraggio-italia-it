<?php

namespace Drupal\references_modal\Controller;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AppendCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\references_modal\Ajax\OpenNestedModalDialogCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class ReferencesModalController.
 *
 * @package Drupal\references_modal\Controller
 */
class ReferencesModalController extends ControllerBase {

  /**
   * The entity form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * The current Request.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * The ReferencesModalController constructor.
   *
   * @param \Drupal\Core\Entity\EntityFormBuilderInterface $form_builder
   *   The form builder.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The current Request.
   */
  public function __construct(EntityFormBuilderInterface $form_builder, RequestStack $request_stack) {
    $this->formBuilder = $form_builder;
    $this->request = $request_stack->getCurrentRequest();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.form_builder'),
      $container->get('request_stack')
    );
  }

  /**
   * Callback for opening the modal form.
   *
   * @param string $entity_type
   *   The entity type name.
   * @param string $bundle
   *   The entity bundle name.
   * @param int|null $id
   *   The entity id or 0 for a new entity.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   An Ajax Response.
   */
  public function openModalForm($entity_type, $bundle, $id = NULL) {
    $storage = $this->entityTypeManager()->getStorage($entity_type);
    $entity = $id ? $storage->load($id) : $storage->create(['type' => $bundle]);
    $operation = $id ? 'default' : 'add';

    $title = $id ? $entity->label() : $this->t('Create');
    $form = $this->formBuilder->getForm($entity, $operation, [
      'is_modal_form' => TRUE,
    ]);
    $options = [
      'width' => '90%',
      'height' => '90%',
    ];

    $dialog_id = 'references_modal_' . $form['#form_id'];
    $response = new AjaxResponse();
    $response->addCommand(new AppendCommand('body', '<div id="' . $dialog_id . '"></div>'));
    $response->addCommand(new OpenNestedModalDialogCommand('#' . $dialog_id, $title, $form, $options));

    return $response;
  }

  /**
   * Access checker method for references_modal.open_modal_form route.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user account.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   Whether or not current user can create the specified entity.
   */
  public function access(AccountInterface $account) {
    $type = $this->request->get('entity_type');

    try {
      $storage = $this->entityTypeManager()->getStorage($type);
    }
    catch (PluginException $e) {
      return AccessResult::forbidden($e->getMessage());
    }

    $id = $this->request->get('id');
    if ($id > 0) {
      $entity = $storage->load($id);
      return AccessResult::allowedIf($entity->access('create', $account));
    }

    try {
      $handler = $this->entityTypeManager()->getAccessControlHandler($type);
    }
    catch (PluginException $e) {
      return AccessResult::forbidden($e->getMessage());
    }

    $bundle = $this->request->get('bundle');
    return AccessResult::allowedIf($handler->createAccess($bundle));
  }

}
