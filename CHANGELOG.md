## [1.0.7] - 2026-02-17

### Summary

refactor(build): deep code analysis engine with 5 supporting modules

### Core

- update includes/bootstrap.php
- update includes/config.php
- update includes/footer.php
- update includes/header.php
- update includes/lang.php

### Docs

- docs: update README

### Config

- config: update goal.yaml

### Other

- build: update Makefile
- update assets/js/main.js
- update index.php
- update lang/en.php
- update lang/pl.php


## [1.0.6] - 2026-02-17

### Summary

feat(docs): deep code analysis engine with 5 supporting modules

### Docs

- docs: update README
- docs: update TODO.md
- docs: update README

### Other

- update .env.example
- update .gitignore
- update .htaccess
- update admin/index.php
- update assets/css/style.css
- update includes/config.php
- update includes/form_handler.php
- update includes/mentor.php
- update includes/rate_limiter.php
- update logs/.gitkeep
- ... and 3 more


## [1.0.5] - 2026-02-17

### Added
- feat: Centralized logging system (`includes/logger.php`) with rotation and structured context.
- feat: Form handler (`includes/form_handler.php`) with CSRF protection and server-side validation.
- feat: Admin dashboard (`admin/index.php`) and log viewer (`admin/view_logs.php`).
- feat: Tool comparison page (`pages/porownanie.php`) comparing CodeReview.pl with 7 other tools.
- feat: CLI log viewer (`logs/view.php`).

### Fixed
- fix: Docker port conflict for Mailhog SMTP (moved to 1026).
- fix: Directory permissions for `logs/` folder in Docker environment.
- fix: Secured `logs/` and `admin/` directories using `.htaccess`.

### Improved
- docs: Updated `README.md` with new project structure and monitoring instructions.
- docs: Updated `TODO.md` to reflect completed security and feature tasks.
- config: Expanded `.env` and `.env.example` with full port and hostname configuration.
- ui: Added styles for comparison tables and form error states in `style.css`.

## [1.0.4] - 2026-02-17

### Summary

fix(docs): deep code analysis engine with 4 supporting modules

### Docs

- docs: update README

### Other

- update .gitignore
- update admin/.htaccess
- update admin/index.php
- update admin/view_logs.php
- update assets/css/style.css
- update database/init.php
- update database/init_db.php
- update docker/logs/access_log
- update docker/logs/error_log
- update includes/config.php
- ... and 9 more


## [1.0.3] - 2026-02-17

### Summary

fix(build): code relationship mapping with 4 supporting modules

### Docs

- docs: update README

### Other

- update .env.example
- build: update Makefile
- update assets/css/style.css
- update assets/img/1-select-type.png
- update assets/img/2-setup-connection.png
- update assets/img/3-docker-terminal.png
- update database/.gitkeep
- update database/codereview.db
- update docker/logs/access_log
- update docker/logs/error_log
- ... and 2 more


## [1.0.2] - 2026-02-17

### Summary

feat(docs): deep code analysis engine with 6 supporting modules

### Docs

- docs: update README
- docs: update TODO.md

### Other

- build: update Makefile


## [1.0.2] - 2026-02-17

### Added

- build: add comprehensive Makefile for development and deployment
- build: Docker development environment with PHP 8.3 + Apache + MySQL
- build: automated production packaging and deployment helpers

### Improved

- docs: update README with Makefile usage instructions
- docs: add Docker development workflow documentation
- docs: enhance Plesk deployment guide

### Fixed

- docs: standardize changelog format
- docs: improve project structure documentation

## [1.0.1] - 2026-02-17

### Summary

feat(docs): configuration management system

### Docs

- docs: update DOCS.md
- docs: update EXAMPLES.md
- docs: update FOOT.md
- docs: update MENU.md
- docs: update README
- docs: update START.md
- docs: update TODO.md
- docs: update README

### Config

- config: update goal.yaml

### Other

- update .dockerignore
- update .gitignore
- update .htaccess
- update 2022/CNAME
- scripts: update readme.sh
- update 2022/readme.txt
- update assets/css/style.css
- update assets/img/.gitkeep
- update assets/js/main.js
- docker: update Dockerfile
- ... and 15 more


