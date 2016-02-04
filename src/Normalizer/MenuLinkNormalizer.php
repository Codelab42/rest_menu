<?php
/**
 * @file
 * Contains \Drupal\rest_menu\Normalizer\MenuLinkNormalizer.
 */

namespace Drupal\rest_menu\Normalizer;

use Drupal\serialization\Normalizer\NormalizerBase;

/**
 * Normalizes/denormalizes Drupal menu links into an array structure.
 */
class MenuLinkNormalizer extends NormalizerBase {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = array('Drupal\Core\Menu\MenuLinkInterface');

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = array()) {
    /** @var \Drupal\Core\Menu\MenuLinkInterface $object */
    if ($object->isEnabled()) {
      $menu_link = [
        'title' => $object->getTitle(),
        'weight' => $object->getWeight(),
        'description' => $object->getDescription(),
        'menu_name' => $object->getMenuName(),
        'provider' => $object->getProvider(),
        'parent' => $object->getParent(),
        'expanded' => $object->isExpanded(),
        'route_name' => $object->getRouteName(),
        'route_parameters' => $object->getRouteParameters(),
        'url' => $object->getUrlObject()->toString(),
        'options' => $object->getOptions(),
        'metadata' => $object->getMetaData(),
      ];
      \Drupal::modulehandler()->alter('menulink_normalize', $menu_link, $object);
      return $menu_link;
    }
  }

}
