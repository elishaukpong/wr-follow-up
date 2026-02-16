.PHONY: help build up down restart shell composer artisan npm migrate seed fresh test pint dev logs

CONTAINER = worship-realm
EXEC = docker exec -it $(CONTAINER)
EXEC_USER = docker exec -it -u www-data $(CONTAINER)

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

# Docker
build: ## Build Docker containers
	docker compose build

up: ## Start Docker containers
	docker compose up -d

down: ## Stop Docker containers
	docker compose down

restart: down up ## Restart Docker containers

logs: ## Tail container logs
	docker compose logs -f

shell: ## Open a shell in the app container
	$(EXEC_USER) bash

# Dependencies
composer: ## Run composer install
	$(EXEC_USER) composer install

npm: ## Run npm install
	$(EXEC_USER) npm install

# Laravel
artisan: ## Run artisan command (usage: make artisan cmd="migrate")
	$(EXEC_USER) php artisan $(cmd)

migrate: ## Run database migrations
	$(EXEC_USER) php artisan migrate

seed: ## Run database seeders
	$(EXEC_USER) php artisan db:seed

fresh: ## Fresh migrate and seed
	$(EXEC_USER) php artisan migrate:fresh --seed

cache: ## Clear and rebuild all caches
	$(EXEC_USER) php artisan optimize:clear
	$(EXEC_USER) php artisan optimize

# Development
dev: ## Start Vite dev server
	$(EXEC_USER) npm run dev

build-assets: ## Build frontend assets for production
	$(EXEC_USER) npm run build

# Testing & Quality
test: ## Run PHPUnit tests
	$(EXEC_USER) php artisan test

pint: ## Run Laravel Pint code formatter
	$(EXEC_USER) ./vendor/bin/pint

# Setup
setup: ## Full project setup (install deps, migrate, build assets)
	$(EXEC_USER) composer install
	$(EXEC_USER) php artisan key:generate --force
	$(EXEC_USER) php artisan migrate --force
	$(EXEC_USER) npm install
	$(EXEC_USER) npm run build
