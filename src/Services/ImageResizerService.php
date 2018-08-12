<?php

namespace Drupal\imageresizer\Services;

use Drupal\image\Entity\ImageStyle;
use Drupal\imageresizer\Classes\ImageResizerConverter;
use Drupal\Core\Config\ConfigFactory;

/**
 * Class ImageResizerService.
 */
class ImageResizerService {

  /**
   * Config service.
   * @var \Drupal\Core\Config\ConfigFactory
   */
  private $configFactory;

  /**
   * Constructs a new ImageResizerService object.
   */
  public function __construct(ConfigFactory $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Get an image resizer string representation of a Drupal image style.
   * 
   * @param \Drupal\image\Entity\ImageStyle $image_style
   *   Drupal image style.
   * @param array $supported_format
   *   The supported format (ex: [jpg, png, gif]).
   */
  public function getImageResizerString(ImageStyle $image_style, array $supported_format = []) {
    $converter = new ImageResizerConverter($image_style, $supported_format);
    return $converter->convert();
  }
}
