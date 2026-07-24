<?php

declare(strict_types=1);

/**
 * @file
 * Project-specific Drupal settings.
 *
 * Environment variables provide production defaults. Local and production
 * override files remain untracked and can replace any value below.
 */

$databases = [];

$settings['config_sync_directory'] = dirname(DRUPAL_ROOT) . '/config/sync';
$settings['content_sync_directory'] = dirname(DRUPAL_ROOT) . '/content/sync';

if (($database = getenv('DRUPAL_DB_NAME')) !== false && $database !== '') {
  $databases['default']['default'] = [
    'database' => $database,
    'username' => getenv('DRUPAL_DB_USER') ?: 'drupal',
    'password' => getenv('DRUPAL_DB_PASSWORD') ?: '',
    'host' => getenv('DRUPAL_DB_HOST') ?: '127.0.0.1',
    'port' => getenv('DRUPAL_DB_PORT') ?: '3306',
    'driver' => getenv('DRUPAL_DB_DRIVER') ?: 'mysql',
    'prefix' => '',
  ];
}

if (($hash_salt = getenv('DRUPAL_HASH_SALT')) !== false && $hash_salt !== '') {
  $settings['hash_salt'] = $hash_salt;
}

if (($trusted_hosts = getenv('DRUPAL_TRUSTED_HOSTS')) !== false && $trusted_hosts !== '') {
  $settings['trusted_host_patterns'] = array_values(array_filter(array_map(
    static fn (string $pattern): string => trim($pattern),
    explode(',', $trusted_hosts),
  )));
}

if (($private_path = getenv('DRUPAL_PRIVATE_PATH')) !== false && $private_path !== '') {
  $settings['file_private_path'] = $private_path;
}

if (($temp_path = getenv('DRUPAL_TEMP_PATH')) !== false && $temp_path !== '') {
  $settings['file_temp_path'] = $temp_path;
}

if (file_exists($app_root . '/' . $site_path . '/settings.prod.php')) {
  include $app_root . '/' . $site_path . '/settings.prod.php';
}

if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
  include $app_root . '/' . $site_path . '/settings.local.php';
}

if (empty($settings['hash_salt'])) {
  throw new RuntimeException(
    'Set DRUPAL_HASH_SALT or define $settings["hash_salt"] in a local settings file.',
  );
}
