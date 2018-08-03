<?php

namespace Drupal\imageresizer\Classes;

use Drupal\image\Entity\ImageStyle;

/**
 * Class ImageResizerConverter.
 */
class ImageResizerConverter {
    
    public $imageStyle;
    
    /**
     * Constructs a new ImageResizerConverter object.
     */
    public function __construct(ImageStyle $image_style) {
        $this->imageStyle = $image_style;
    }
    
    /**
     * Convert the Drupal image style into a Image Resizer string
     */
    public function convert() {
      $data =[];
      $effects = $this->imageStyle->getEffects();
      $infos = [];
      foreach ($effects->getIterator() as $effect) {
        $configuration= $effect->getConfiguration();
        kint($configuration);
        $transform_infos = [];
        if (!empty($configuration['id'])) {
          $transform_infos['mode'] = $this->extractEffectMode($configuration['id']);
        }
        if (!empty($configuration['data'])) {
          $transform_infos['parameters'] = $this->extractEffectData($configuration['data']);
        }
      }
    }
    
    public function extractEffectMode($id) {
      switch ($id) {
        case 'image_scale':
          $mode = 'pad';
          break;
          
        case 'image_crop':
          $mode = 'pad';
          break;
          
        case 'image_resize':
          $mode = 'pad';
          break;
          
        case 'image_rotate' || 'image_convert':
          $mode = 'pad';
          break;

        default:
          $mode = 'pad';
          break;
      }
      return $mode;
    }

    public function extractEffectData($data) {
      $infos = [];
      foreach ($data as $property_name => $property) {
        switch($property_name) {
          case 'upscale':
            $infos['scale'] = ($property) ? 'both' : 'canvas';
            break;

          default:
            $infos[$property_name] = $property;
            break;
        }
      }
      return $infos;
    }
}
