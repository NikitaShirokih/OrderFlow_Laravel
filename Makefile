COMPOSE = docker compose
APP = $(COMPOSE) exec app

.PHONY: build up down restart logs ps bash composer artisan migrate fresh test cache-clear key permissions analyse lint format quality

build:
	$(COMPOSE) build --no-cache

up:
	$(COMPOSE) up -d

down:
	$(COMPOSE) down

restart:
	$(COMPOSE) down
	$(COMPOSE) up -d

logs:
	$(COMPOSE) logs -f

ps:
	$(COMPOSE) ps

bash:
	$(APP) bash

composer:
	$(APP) composer install

artisan:
	$(APP) php artisan

migrate:
	$(APP) php artisan migrate

fresh:
	$(APP) php artisan migrate:fresh --seed

test:
	$(APP) php artisan test

cache-clear:
	$(APP) php artisan optimize:clear

key:
	$(APP) php artisan key:generate

permissions:
	sudo chown -R $$(id -u):$$(id -g) storage bootstrap/cache
	chmod -R ug+rwX storage bootstrap/cache

analyse:
	$(APP) vendor/bin/phpstan analyse

lint:
	$(APP) vendor/bin/pint --test

format:
	$(APP) vendor/bin/pint

quality:
	$(APP) vendor/bin/pint --test
	$(APP) vendor/bin/phpstan analyse
	$(APP) php artisan test