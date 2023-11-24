<?php


/**
 * Implements hook_theme().
 */
function initial_theme() {

  $theme['node__article'] = [
    'template' => 'node--article',
    'base hook' => 'node',
  ];

  return $theme;
}
