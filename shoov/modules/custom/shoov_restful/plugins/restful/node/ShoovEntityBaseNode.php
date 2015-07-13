<?php

/**
 * @file
 * Contains \ShoovEntityBaseNode.
 */

abstract class ShoovEntityBaseNode extends \RestfulEntityBaseNode {

  /**
   * Overrides \RestfulEntityBaseNode::publicFieldsInfo().
   */
  public function publicFieldsInfo() {
    $public_fields = parent::publicFieldsInfo();

    $public_fields['created'] = array(
      'property' => 'created',
    );

    $public_fields['updated'] = array(
      'property' => 'changed',
    );

    if (field_info_instance($this->getEntityType(), OG_AUDIENCE_FIELD, $this->getBundle())) {
      $public_fields['company'] = array(
        'property' => OG_AUDIENCE_FIELD,
        'resource' => array(
          'company' => array(
            'name' => 'companies',
            'full_view' => FALSE,
          )
        ),
      );
    }

    return $public_fields;
  }

  /**
   * Process callback, Remove Drupal specific events from the image array.
   *
   * @param array $value
   *   The image array.
   *
   * @return array
   *   A cleaned image array.
   */
  protected function imageProcess($value) {
    if (static::isArrayNumeric($value)) {
      $output = array();
      foreach ($value as $item) {
        $output[] = $this->imageProcess($item);
      }
      return $output;
    }

    $defaultStyles = shoov_screenshot_image_default_styles();
    $styles = array();
    foreach ($value['image_styles'] as $style => $url) {
      $styles[$style] = array();
      $styles[$style]['url'] = $url;
      $styles[$style]['label'] = $defaultStyles[$style]['label'];
      $effect = reset($defaultStyles[$style]['effects']);
      $styles[$style]['width'] = strval($effect['data']['width']);
    }
    return array(
      'id' => $value['fid'],
      'self' => file_create_url($value['uri']),
      'filemime' => $value['filemime'],
      'filesize' => $value['filesize'],
      'width' => $value['width'],
      'height' => $value['height'],
      'styles' => $styles,
    );
  }
}
