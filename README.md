# Drupal Starter

A production-oriented Drupal 11 starter with a relocated `web/` document root, configuration synchronization, a small custom module, Composer-managed dependencies, CI, Dependabot, and manual SSH deployment.

## Baseline

- Drupal `^11.4`
- PHP `8.3` or `8.4`
- Composer `2.7+`
- Drush `^13.7`
- MySQL `8.0+`, MariaDB `10.6+`, PostgreSQL `16+`, or SQLite `3.45+`
- Apache, Nginx, or another PHP-capable web server

## Create a project

Generate a repository from this template or clone it, then run:

```bash
composer install
cp web/sites/default/settings.local.php.example web/sites/default/settings.local.php
```

Edit `settings.local.php` with your local database credentials and replace the example hash salt. A suitable local salt can be generated with:

```bash
php -r 'echo bin2hex(random_bytes(32)), PHP_EOL;'
```

Install the site from the committed configuration and create a login link:

```bash
vendor/bin/drush site:install --existing-config -y
vendor/bin/drush user:login
```

The first Composer install creates `composer.lock` when one is not present. Commit that lock file in projects generated from this starter so builds and deployments remain reproducible.

## Local web server

Point the document root to `web/`, never to the repository root.

An Nginx example is available at `.local/nginx/sample.conf`. Update its project path and PHP-FPM address for your machine. The default Drush URI is configured in `drush/drush.yml` as `http://drupal.localhost:8080`.

## Environment configuration

Production can be configured without a committed settings file override:

| Variable | Purpose | Default |
| --- | --- | --- |
| `DRUPAL_DB_NAME` | Database name; enables environment-based DB configuration | none |
| `DRUPAL_DB_USER` | Database user | `drupal` |
| `DRUPAL_DB_PASSWORD` | Database password | empty |
| `DRUPAL_DB_HOST` | Database host | `127.0.0.1` |
| `DRUPAL_DB_PORT` | Database port | `3306` |
| `DRUPAL_DB_DRIVER` | Drupal database driver | `mysql` |
| `DRUPAL_HASH_SALT` | Required application hash salt | none |
| `DRUPAL_TRUSTED_HOSTS` | Comma-separated trusted-host regex patterns | none |
| `DRUPAL_PRIVATE_PATH` | Private file directory | Drupal default |
| `DRUPAL_TEMP_PATH` | Temporary file directory | Drupal default |

`web/sites/default/settings.prod.php` and `settings.local.php` are also loaded when present and are ignored by Git.

## Common commands

```bash
# Clear caches.
vendor/bin/drush cache:rebuild

# Export and import configuration.
vendor/bin/drush config:export -y
vendor/bin/drush config:import -y

# Apply database updates, config imports, deploy hooks, and cache rebuilds.
vendor/bin/drush deploy -y

# Run cron.
vendor/bin/drush cron

# Export and restore a database.
mkdir -p database
vendor/bin/drush sql:dump --result-file=database/local.sql
vendor/bin/drush sql:drop -y
vendor/bin/drush sql:cli < database/local.sql
```

Database dumps under `database/` are intentionally ignored.

## Updating dependencies

Review updates before applying them:

```bash
composer outdated --direct
composer update --with-all-dependencies
composer audit
vendor/bin/drush deploy -y
```

Dependabot checks Composer packages and GitHub Actions weekly. The CI workflow validates Composer metadata, installs dependencies on PHP 8.3 and 8.4, audits packages, and checks custom PHP syntax.

## Deployment

The `Deploy Drupal` workflow is manual and targets the protected `production` environment. Configure these repository or environment secrets:

- `DEPLOY_HOST`
- `DEPLOY_PORT`
- `DEPLOY_USER`
- `DEPLOY_PATH`
- `DEPLOY_SSH_KEY`
- `DEPLOY_KNOWN_HOSTS`

Generate the known-host entry from a trusted network and store the complete output as `DEPLOY_KNOWN_HOSTS`:

```bash
ssh-keyscan -p 22 -H example.com
```

The remote checkout must be on `main`, have Composer available on `PATH`, and allow the deploy user to run `vendor/bin/drush deploy -y`.

## Project layout

```text
.github/                 CI, deployment, and dependency automation
config/sync/             Exported Drupal configuration
content/sync/            Content Entity Sync data
 database/                Local database dump directory
 drush/                   Drush configuration
 web/                     Public document root
 web/modules/initial/     Project-specific custom module
 web/sites/default/       Shared and environment-specific settings
```

## Security notes

- Never commit `settings.local.php`, `settings.prod.php`, private keys, database dumps, or `.env` files.
- Keep the document root set to `web/`.
- Use a unique `DRUPAL_HASH_SALT` for each environment.
- Protect deployment with a GitHub Environment, required reviewers, and restricted secrets.
- Run `composer audit` and apply Drupal security releases promptly.
