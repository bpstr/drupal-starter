<?php

declare(strict_types=1);

/**
 * Implements hook_theme().
 */
function initial_theme(): array {
  return [
    'node__article' => [
      'template' => 'node--article',
      'base hook' => 'node',
    ],
  ];
}
