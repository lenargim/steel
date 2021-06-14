serve:
	php bin/console server:run
db:
	php bin/console doctrine:schema:update --force
settings_site:
	php bin/console make:entity SiteBundle --regenerate
settings:
	php bin/console make:entity App --regenerate
cc:
	php bin/console cache:clear
js-dev:
	npm run dev
js-prod:
	npm run build
	php bin/console ckeditor:install
	php bin/console assets:install --symlink
sitemap:
	php bin/console site:sitemap:update
import:
	php bin/console cache:clear
recalc:
	php bin/console site:pages:recalc
	php bin/console site:pages:set_path
	php bin/console site:sitemap:update
	php bin/console cache:clear

