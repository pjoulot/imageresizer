<?php

namespace Drupal\imageresizer\Classes;

use Drupal\image\Entity\ImageStyle;

/**
 * Class ImageResizerConverter.
 */
class ImageResizerConverter {

    public $imageStyle;

    const SUPPORTED_FORMATS = ['jpg', 'png', 'gif'];

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
        $transform_infos = [];
        if (!empty($configuration['id'])) {
          $existing_mode = !empty($infos['mode']) ? $infos['mode'] : NULL;
          $transform_infos['mode'] = $this->extractEffectMode($existing_mode, $configuration['id']);
        }
        if (!empty($configuration['data'])) {
          $transform_infos = array_merge($transform_infos, $this->extractEffectData($configuration['data']));
        }

        // Merge the informations of this iteration.
        $infos = array_merge($infos, $transform_infos);
      }
      $string = implode(
        ' ',
        array_map(
          function ($v, $k) { return sprintf("%s=%s", $k, $v); },
          $infos,
          array_keys($infos)
        )
      );
      return $string;
    }

    public function extractEffectMode($existing_mode, $id) {
      switch ($id) {
        case 'image_scale':
          $mode = 'pad';
          break;

        case 'image_crop':
          $mode = 'crop';
          break;

        case 'image_resize':
          $mode = 'pad';
          break;

        case 'image_rotate' || 'image_convert':
          $mode = NULL;
          break;

        default:
          $mode = 'pad';
          break;
      }

      // If we have no mode, choose the default one.
      if ($mode === NULL && $existing_mode === NULL) {
        $mode = 'pad';
      }
      else {
        $mode = ($mode === NULL) ? $existing_mode : $mode;
      }

      return $mode;
    }

    public function extractEffectData($data) {
      $infos = [];
      foreach ($data as $property_name => $property) {
        switch ($property_name) {
          case 'upscale':
            $infos['scale'] = ($property) ? 'both' : 'canvas';
            break;
          case 'anchor':
            $infos['anchor'] = $this->getAnchorValue($property);
            break;
          case 'extension':
            $infos['format'] = $this->getFormatValue($property);
            break;
          default:
            $infos[$property_name] = $property;
            break;
        }
      }
      return $infos;
    }

    public function getFormatValue($value) {
      return in_array($value, SUPPORTED_FORMATS) ? $value : 'jpg';
    }

    public function getAnchorValue($value) {
      switch ($value) {
        case 'left-top':
          $anchor= 'topleft';
          break;

        case 'center-top':
          $anchor= 'topcenter';
          break;

        case 'right-top':
          $anchor= 'topright';
          break;

        case 'left-center':
          $anchor= 'middleleft';
          break;

        case 'center-center':
          $anchor= 'middlecenter';
          break;

        case 'right-center':
          $anchor= 'middleright';
          break;

        case 'left-bottom':
          $anchor= 'bottomleft';
          break;

        case 'center-bottom':
          $anchor= 'bottomcenter';
          break;

        case 'right-bottom':
          $anchor= 'bottomright';
          break;

        default:
          $anchor = 'middlecenter';
          break;
      }
      return $anchor;
    }
}
