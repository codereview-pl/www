###############################################
# CodeReview.pl — Makefile
# PHP website with Docker dev environment
# Optimized for Plesk deployment
###############################################

# Default target
.PHONY: help
help: ## Show this help message
	@echo "CodeReview.pl — Available commands:"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}'

# Docker development environment
.PHONY: up down rebuild logs shell clean
up: ## Start Docker development environment
	docker-compose up -d

down: ## Stop Docker development environment
	docker-compose down

rebuild: ## Rebuild and start Docker containers
	docker-compose down
	docker-compose build --no-cache
	docker-compose up -d

logs: ## Show Docker logs
	docker-compose logs -f

shell: ## Open shell in web container
	docker-compose exec web bash

clean: ## Clean Docker containers and volumes
	docker-compose down -v
	docker system prune -f

# Development utilities
.PHONY: install-dev test-dev lint-dev
install-dev: ## Install development dependencies
	@echo "Installing development tools..."
	@if [ ! -f composer.json ]; then echo "No composer.json found"; exit 1; fi
	docker-compose exec web composer install

test-dev: ## Run tests in development environment
	docker-compose exec web php -v
	@echo "Tests would run here (add phpunit or similar)"

lint-dev: ## Run code linting
	docker-compose exec web php -l index.php
	docker-compose exec web php -l includes/*.php
	docker-compose exec web php -l pages/*.php

# Production deployment helpers
.PHONY: build-prod deploy-prod backup-prod
build-prod: ## Build production package (exclude Docker files)
	@echo "Creating production package..."
	mkdir -p dist
	rsync -av --exclude-from=.dockerignore \
		--exclude='docker/' \
		--exclude='docker-compose.yml' \
		--exclude='Makefile' \
		--exclude='.git/' \
		--exclude='README.md' \
		./ dist/
	tar -czf codereview-pl-$(shell date +%Y%m%d).tar.gz -C dist .
	@echo "Production package created: codereview-pl-$(shell date +%Y%m%d).tar.gz"

deploy-prod: build-prod ## Deploy to production (requires FTP/SFTP setup)
	@echo "Deploy to production server:"
	@echo "1. Upload codereview-pl-$(shell date +%Y%m%d).tar.gz to Plesk"
	@echo "2. Extract to /var/www/vhosts/codereview.pl/httpdocs/"
	@echo "3. Set permissions: chown -R www-data:www-data httpdocs/"

backup-prod: ## Backup current production (requires SSH access)
	@echo "Backup command for production server:"
	@echo "ssh user@server 'tar -czf /backup/codereview-pl-$(shell date +%Y%m%d).tar.gz /var/www/vhosts/codereview.pl/httpdocs/'"

# Database operations
.PHONY: db-backup db-restore db-reset
db-backup: ## Backup development database
	docker-compose exec db mysqldump -u root -proot_secret codereview > backup-$(shell date +%Y%m%d).sql
	@echo "Database backed up to backup-$(shell date +%Y%m%d).sql"

db-restore: ## Restore database from backup (usage: make db-restore BACKUP=file.sql)
	@if [ -z "$(BACKUP)" ]; then echo "Usage: make db-restore BACKUP=backup-file.sql"; exit 1; fi
	docker-compose exec -T db mysql -u root -proot_secret codereview < $(BACKUP)

db-reset: ## Reset development database
	docker-compose down -v
	docker-compose up -d db
	@echo "Database reset. Waiting for initialization..."
	sleep 10

# Maintenance
.PHONY: update-deps check-deps
update-deps: ## Update Composer dependencies
	@if [ -f composer.json ]; then docker-compose exec web composer update; else echo "No composer.json found"; fi

check-deps: ## Check for security vulnerabilities
	@if [ -f composer.json ]; then docker-compose exec web composer audit; else echo "No composer.json found"; fi

# Quick access URLs
.PHONY: urls
urls: ## Show development URLs
	@echo "Development URLs:"
	@echo "  Website:    http://localhost:8080"
	@echo "  phpMyAdmin: http://localhost:8081"
	@echo "  MailHog:    http://localhost:8025"
	@echo "  Database:   localhost:3306"

# Version and info
.PHONY: version info
version: ## Show current version
	@if [ -f VERSION ]; then cat VERSION; else echo "unknown"; fi

info: ## Show project information
	@echo "Project: CodeReview.pl Website"
	@echo "Version: $$(make version)"
	@echo "PHP: $$(docker-compose exec web php -v | head -1 | cut -d' ' -f2)"
	@echo "MySQL: $$(docker-compose exec db mysql -V | cut -d' ' -f3)"
	@echo "Apache: $$(docker-compose exec web apache2 -v | cut -d' ' -f3 | cut -d'(' -f1)"
