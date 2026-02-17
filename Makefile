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

# Quick start
.PHONY: start
start: install up ## Quick start: install and start development environment

# Docker development environment
.PHONY: up down rebuild logs shell clean up-mysql
up: ## Start Docker development environment (SQLite only)
	docker-compose up -d
	@sleep 10
	@echo "Running health check..."
	@bash ./scripts/healthcheck.sh || true
	@echo ""
	@echo "Running deep diagnostic..."
	@bash ./scripts/diagnostic.sh || true
	@echo ""
	$(MAKE) logs

up-mysql: ## Start Docker with MySQL profile
	docker-compose --profile mysql up -d
	@sleep 10
	@bash ./scripts/healthcheck.sh || true

down: ## Stop Docker development environment
	docker-compose down

down-mysql: ## Stop Docker with MySQL profile
	docker-compose --profile mysql down

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

# Testing
.PHONY: test-e2e
test-e2e: ## Run E2E tests against running environment
	@bash ./scripts/test-e2e.sh http://localhost:8080

# Development utilities
.PHONY: install-dev test-dev lint-dev install
install: ## Install project (create .env from .env.example)
	@if [ ! -f .env ]; then \
		echo "Creating .env from .env.example..."; \
		cp .env.example .env; \
		echo "✓ .env created successfully"; \
		echo "⚠️  Please review and update .env with your configuration"; \
	else \
		echo "✓ .env already exists"; \
	fi
	@echo "Installing development dependencies..."
	@if [ ! -f composer.json ]; then echo "No composer.json found"; exit 1; fi
	docker-compose exec web composer install

install-dev: ## Install development dependencies only
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
		--exclude='TODO.md' \
		--exclude='CHANGELOG.md' \
		--exclude='.env.example' \
		--exclude='.env' \
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
.PHONY: db-backup db-restore db-reset db-init
db-backup: ## Backup development database (SQLite)
	docker-compose exec web cp /var/www/vhosts/codereview.pl/httpdocs/database/codereview.db ./backup-$(shell date +%Y%m%d).db
	@echo "SQLite database backed up to backup-$(shell date +%Y%m%d).db"

db-backup-mysql: ## Backup MySQL database
	docker-compose exec db mysqldump -u root -proot_secret codereview > backup-mysql-$(shell date +%Y%m%d).sql
	@echo "MySQL database backed up to backup-mysql-$(shell date +%Y%m%d).sql"

db-restore: ## Restore SQLite database (usage: make db-restore BACKUP=file.db)
	@if [ -z "$(BACKUP)" ]; then echo "Usage: make db-restore BACKUP=backup-file.db"; exit 1; fi
	docker-compose exec web cp $(BACKUP) /var/www/vhosts/codereview.pl/httpdocs/database/codereview.db
	@echo "SQLite database restored from $(BACKUP)"

db-restore-mysql: ## Restore MySQL database (usage: make db-restore-mysql BACKUP=file.sql)
	@if [ -z "$(BACKUP)" ]; then echo "Usage: make db-restore-mysql BACKUP=backup-file.sql"; exit 1; fi
	docker-compose exec -T db mysql -u root -proot_secret codereview < $(BACKUP)

db-reset: ## Reset SQLite database
	docker-compose exec web rm -f /var/www/vhosts/codereview.pl/httpdocs/database/codereview.db
	docker-compose exec web touch /var/www/vhosts/codereview.pl/httpdocs/database/codereview.db
	@echo "SQLite database reset"

db-reset-mysql: ## Reset MySQL database
	docker-compose down -v
	docker-compose --profile mysql up -d db
	@echo "MySQL database reset. Waiting for initialization..."
	sleep 10

db-init: ## Initialize database with schema (PHP script)
	@docker-compose exec web php database/init_db.php

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
	@echo "  SQLite DB:   ./database/codereview.db"
	@echo "  MailHog:     http://localhost:8025"
	@echo ""
	@echo "MySQL (optional - use --profile mysql):"
	@echo "  phpMyAdmin:  http://localhost:8081"
	@echo "  Database:    localhost:3306"

# Diagnostic
.PHONY: diagnostic
diagnostic: ## Check active ports and connection status
	@echo "=== CodeReview.pl Diagnostic ==="
	@echo ""
	@echo "Port   Service          Status"
	@echo "------ ---------------- --------"
	@if curl -s -o /dev/null -w "%{http_code}" http://localhost:8080 2>/dev/null | grep -q "200"; then \
		echo "8080   Web (HTTP)       ✅ ACTIVE"; \
	else \
		echo "8080   Web (HTTP)       ❌ INACTIVE"; \
	fi
	@if curl -s -o /dev/null -w "%{http_code}" https://localhost:8443 2>/dev/null | grep -q "200"; then \
		echo "8443   Web (HTTPS)      ✅ ACTIVE"; \
	else \
		echo "8443   Web (HTTPS)      ❌ INACTIVE"; \
	fi
	@if curl -s -o /dev/null -w "%{http_code}" http://localhost:3306 2>/dev/null | grep -q "200\| refused"; then \
		echo "3306   MySQL            ⚠️  AVAILABLE (optional)"; \
	else \
		echo "3306   MySQL            ❌ INACTIVE (optional)"; \
	fi
	@if curl -s -o /dev/null -w "%{http_code}" http://localhost:8081 2>/dev/null | grep -q "200"; then \
		echo "8081   phpMyAdmin       ✅ ACTIVE"; \
	else \
		echo "8081   phpMyAdmin       ❌ INACTIVE (optional)"; \
	fi
	@if curl -s -o /dev/null -w "%{http_code}" http://localhost:8025 2>/dev/null | grep -q "200"; then \
		echo "8025   Mailhog UI       ✅ ACTIVE"; \
	else \
		echo "8025   Mailhog UI       ❌ INACTIVE"; \
	fi
	@echo ""
	@echo "Docker containers:"
	@docker-compose ps --format "table {{.Name}}\t{{.Status}}\t{{.Ports}}" 2>/dev/null || echo "Docker not running"
	@echo ""
	@echo "Website response:"
	@curl -s -o /dev/null -w "HTTP Status: %{http_code}, Time: %{time_total}s\n" http://localhost:8080 2>/dev/null || echo "Not responding"

# Diagnostic
.PHONY: diagnostic
diagnostic: ## Run deep diagnostic check
	@bash ./scripts/diagnostic.sh

.PHONY: healthcheck
healthcheck: ## Run health check with auto-fix
	@bash ./scripts/healthcheck.sh

.PHONY: fix
fix: ## Fix common issues (usage: make fix [all|web|network|volumes])
	@bash ./scripts/fix.sh $(FIX)

# Version and info
.PHONY: version info
version: ## Show current version
	@if [ -f VERSION ]; then cat VERSION; else echo "unknown"; fi

info: ## Show project information
	@echo "Project: CodeReview.pl Website"
	@echo "Version: $$(make version)"
	@echo "PHP: $$(docker-compose exec web php -v | head -1 | cut -d' ' -f2)"
	@echo "Database: SQLite (default) - ./database/codereview.db"
	@echo "MySQL: Available with --profile mysql"
