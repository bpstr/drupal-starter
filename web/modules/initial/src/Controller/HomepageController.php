<?php

declare(strict_types=1);

namespace Drupal\initial\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Initial routes.
 */
final class HomepageController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function __invoke(): array {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('Hello world!'),
    ];

    return $build;
  }

}
