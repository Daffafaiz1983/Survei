# Makefile untuk SurveiV2 Docker Management

.PHONY: help dev prod build stop clean logs shell migrate seed test

# Default target
help: ## Menampilkan bantuan
	@echo "SurveiV2 Docker Management"
	@echo "========================="
	@echo ""
	@echo "Available commands:"
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

# Development commands
dev: ## Menjalankan environment development
	@echo "Starting development environment..."
	docker-compose -f docker-compose.dev.yml up -d
	@echo "Development environment started!"
	@echo "App: http://localhost:8000"
	@echo "MailHog: http://localhost:8025"

dev-build: ## Build development containers
	@echo "Building development containers..."
	docker-compose -f docker-compose.dev.yml build --no-cache

dev-logs: ## Melihat logs development
	docker-compose -f docker-compose.dev.yml logs -f

dev-stop: ## Menghentikan development environment
	@echo "Stopping development environment..."
	docker-compose -f docker-compose.dev.yml down

# Production commands
prod: ## Menjalankan environment production
	@echo "Starting production environment..."
	docker-compose up -d
	@echo "Production environment started!"
	@echo "App: http://localhost"

prod-build: ## Build production containers
	@echo "Building production containers..."
	docker-compose build --no-cache

prod-logs: ## Melihat logs production
	docker-compose logs -f

prod-stop: ## Menghentikan production environment
	@echo "Stopping production environment..."
	docker-compose down

# Build commands
build: ## Build semua containers
	@echo "Building all containers..."
	docker-compose build --no-cache
	docker-compose -f docker-compose.dev.yml build --no-cache

# Stop commands
stop: ## Menghentikan semua containers
	@echo "Stopping all containers..."
	docker-compose down
	docker-compose -f docker-compose.dev.yml down

# Clean commands
clean: ## Membersihkan containers dan volumes
	@echo "Cleaning up containers and volumes..."
	docker-compose down -v --remove-orphans
	docker-compose -f docker-compose.dev.yml down -v --remove-orphans
	docker system prune -f

clean-all: ## Membersihkan semua (containers, volumes, images)
	@echo "Cleaning up everything..."
	docker-compose down -v --remove-orphans
	docker-compose -f docker-compose.dev.yml down -v --remove-orphans
	docker system prune -af
	docker volume prune -f

# Logs commands
logs: ## Melihat logs semua services
	docker-compose logs -f

logs-app: ## Melihat logs aplikasi
	docker-compose logs -f app

logs-db: ## Melihat logs database
	docker-compose logs -f db

logs-redis: ## Melihat logs Redis
	docker-compose logs -f redis

# Shell commands
shell: ## Masuk ke container aplikasi
	docker-compose exec app sh

shell-dev: ## Masuk ke container aplikasi development
	docker-compose -f docker-compose.dev.yml exec app sh

shell-db: ## Masuk ke container database
	docker-compose exec db mysql -u surveiv2_user -p surveiv2

# Laravel commands
migrate: ## Menjalankan database migrations
	docker-compose exec app php artisan migrate

migrate-fresh: ## Menjalankan fresh migrations
	docker-compose exec app php artisan migrate:fresh

seed: ## Menjalankan database seeders
	docker-compose exec app php artisan db:seed

migrate-seed: ## Menjalankan migrations dan seeders
	docker-compose exec app php artisan migrate --seed

# Cache commands
cache-clear: ## Membersihkan semua cache
	docker-compose exec app php artisan optimize:clear

cache-config: ## Cache konfigurasi
	docker-compose exec app php artisan config:cache

cache-route: ## Cache routes
	docker-compose exec app php artisan route:cache

cache-view: ## Cache views
	docker-compose exec app php artisan view:cache

# Test commands
test: ## Menjalankan tests
	docker-compose exec app php artisan test

test-coverage: ## Menjalankan tests dengan coverage
	docker-compose exec app php artisan test --coverage

# Queue commands
queue-work: ## Menjalankan queue worker
	docker-compose exec app php artisan queue:work

queue-failed: ## Melihat failed jobs
	docker-compose exec app php artisan queue:failed

queue-retry: ## Retry failed jobs
	docker-compose exec app php artisan queue:retry all

# Setup commands
setup: ## Setup aplikasi (migrate, seed, cache)
	@echo "Setting up application..."
	docker-compose exec app php artisan key:generate --force
	docker-compose exec app php artisan migrate --force
	docker-compose exec app php artisan db:seed --force
	docker-compose exec app php artisan storage:link
	docker-compose exec app php artisan config:cache
	docker-compose exec app php artisan route:cache
	docker-compose exec app php artisan view:cache
	@echo "Setup completed!"

# Status commands
status: ## Melihat status containers
	@echo "Production containers:"
	docker-compose ps
	@echo ""
	@echo "Development containers:"
	docker-compose -f docker-compose.dev.yml ps

# Restart commands
restart: ## Restart semua services
	docker-compose restart

restart-app: ## Restart aplikasi
	docker-compose restart app

restart-db: ## Restart database
	docker-compose restart db

restart-redis: ## Restart Redis
	docker-compose restart redis
