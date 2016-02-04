<?php
/**
 * @file
 * Contains \Drupal\rest_menu\Normalizer\MenuLinkTreeElementNormalizer.
 */

namespace Drupal\rest_menu\Normalizer;

use Drupal\serialization\Normalizer\NormalizerBase;

/**
 * Normalizes/denormalizes Drupal menu link tree elements into an array.
 */
class MenuLinkTreeElementNormalizer extends NormalizerBase {

  /**
   * The interface or class that this Normalizer supports.
   *
   * @var array
   */
  protected $supportedInterfaceOrClass = array('Drupal\Core\Menu\MenuLinkTreeElement');

  /**
   * {@inheritdoc}
   */
  public function normalize($object, $format = NULL, array $context = array()) {
    return [
      'link' => $this->serializer->normalize($object->link, $format, $context),
      'has_children' => $object->hasChildren,
      'children' => $this->serializer->normalize($object->subtree, $format, $context),
      'depth' => $object->depth,
      'in_active_trail' => $object->inActiveTrail,
    ];
  }

}
