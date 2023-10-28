# drupal-starter

## Initial local setup

1. Generate a repository from [this template](https://github.com/bpstr/drupal-starter/generate)
2. Set up local development database credentials in `web/sites/default/settings.local.php`
3. Set up drush local site url in `drush/drush.yml`
4. Set up nginx configuration according to the sample in `.local/nginx/sample.conf`
5. Run the following snippet in project root to log in your brand-new site:
```bash
composer install
drush si --existing-config
drush uli
drush cron
drush cr
```





### Detailed nginx configuration for local development

todo.
