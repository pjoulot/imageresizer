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
   */
  public function getImageResizerString(ImageStyle $image_style) {
    $converter = new ImageResizerConverter($image_style);
    return $converter->convert();
  }
}
