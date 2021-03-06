<?php

namespace Drupal\rest_normalizations\Normalizer;

use Drupal\serialization\Normalizer\ContentEntityNormalizer;
use Drupal\Core\Entity\Entity;
use Drupal\file\Entity\File;
use Drupal;

class ImageNormalizer extends ContentEntityNormalizer {
  protected $supportedInterfaceOrClass = 'Drupal\file\FileInterface';

	public function supportsNormalization($data, $format = NULL) {
    if (!parent::supportsNormalization($data, $format)) {
      return FALSE;
    }

    $mimetype = explode('/', $data->getMimeType());
    if ($mimetype[0] != 'image') {
      return FALSE;
    }

    return TRUE;
  }

  public function normalize($entity, $format = NULL, array $context = []) {
    $data = parent::normalize($entity, $format, $context);

    $path = $entity->getFileUri();

    $data['style_urls'] = [];
    $styles = $this->entityManager->getStorage('image_style')->loadMultiple();
    foreach ($styles as $style) {
      $data['style_urls'][$style->id()] = $style->buildUrl($path);
    }

    return $data;
  }
}