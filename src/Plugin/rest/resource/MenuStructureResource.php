<?php

/**
 * @file
 * Contains \Drupal\rest_menu\Plugin\rest\resource\MenuStructureResource.
 */

namespace Drupal\rest_menu\Plugin\rest\resource;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\system\Entity\Menu;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Drupal\Core\Menu\MenuTreeParameters;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Provides a resource for Drupal menu structures.
 *
 * @RestResource(
 *   id = "menu_structure",
 *   label = @Translation("Drupal menu structure and links."),
 *   uri_paths = {
 *     "canonical" = "/menustructure/{menu}"
 *   }
 * )
 */
class MenuStructureResource extends ResourceBase {

  /**
   *  A instance of entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * The menu tree service.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $menuTree;

  /**
   * Constructs a Drupal\rest\Plugin\ResourceBase object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param array $serializer_formats
   *   The available serialization formats.
   * @param \Psr\Log\LoggerInterface $logger
   *   A logger instance.
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   An instance of entity manager.
   * @param \Drupal\Core\Menu\MenuLinkTreeInterface $menu_tree
   *   The menu tree service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, array $serializer_formats, LoggerInterface $logger, EntityManagerInterface $entity_manager, MenuLinkTreeInterface $menu_tree) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $serializer_formats, $logger);
    $this->entityManager = $entity_manager;
    $this->menuTree = $menu_tree;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->getParameter('serializer.formats'),
      $container->get('logger.factory')->get('rest'),
      $container->get('entity.manager'),
      $container->get('menu.link_tree')
    );
  }

  /**
   * Responds to GET requests.
   *
   * Returns the menu structure for the given menu name.
   *
   * @param string $menu_name
   *   The menu name.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The response containing the log entry.
   *
   *
   */
  public function get($menu_name) {
    // @TODO: make $menu parameter a type-hinted MenuInterface parameter
    $menu = Menu::load($menu_name);
    if ($menu) {
      $tree = $this->menuTree->load($menu->id(), new MenuTreeParameters());
      // Get proper info from tree and return it.
      $record = [];
      return new ResourceResponse($record);
    }
    throw new HttpException(t('No valid menu ID was provided.'));
  }
}
