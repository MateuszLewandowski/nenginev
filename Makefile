DOCKER = '.docker'

build:
	cd $(DOCKER) && docker compose up --build -d

start:
	cd $(DOCKER) && docker compose up --target dev -d

stop:
	cd $(DOCKER) && docker compose stop

down:
	cd $(DOCKER) && docker compose down

logs:
	cd $(DOCKER) && docker compose logs --tail=0 --follow

sh:
	cd $(DOCKER) && docker compose exec nenginev_php sh

cs-files:
	cd $(DOCKER) && docker compose exec -T nenginev_php sh -c "vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix"

cs:
	cd $(DOCKER) && docker compose exec -T nenginev_php sh -c "vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix $(FILES)"

unit:
	cd $(DOCKER) && docker compose exec nenginev_php sh -c "vendor/bin/phpunit"

behat:
	cd $(DOCKER) && docker compose exec nenginev_php sh -c "export APP_ENV='test'; vendor/bin/behat;"

health:
	cd $(DOCKER) && docker compose exec nenginev_php sh -c "bin/console health:check"

db-migration:
	cd $(DOCKER) && docker compose exec nenginev_php sh -c "\
		APP_ENV=dev; \
		bin/console doctrine:migrations:diff -n --configuration=config/packages/migrations/default.yaml; \
	"

db-migrate:
	cd $(DOCKER) && docker compose exec nenginev_php sh -c "\
		APP_ENV=dev; \
        bin/console doctrine:migrations:migrate -n --configuration=config/packages/migrations/default.yaml; \
	"

db-reset:
	cd $(DOCKER) && docker compose exec nenginev_php sh -c "\
		APP_ENV='dev'; \
		bin/console doctrine:database:drop --if-exists --force; \
		bin/console doctrine:database:create; \
		bin/console doctrine:migrations:migrate -n --configuration=config/packages/migrations/default.yaml; \
	"