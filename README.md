# CodeReview.pl / coboarding.com — Website

Strona PHP zoptymalizowana pod **Plesk Obsidian** z Docker dev environment.

## Struktura (Plesk-compatible)

```
httpdocs/                         ← Plesk document root
├── index.php                     ← STRONA GŁÓWNA (w katalogu głównym!)
├── .htaccess                     ← Clean URLs + security
├── admin/
│   ├── index.php                ← Dashboard administratora
│   └── view_logs.php            ← Przeglądarka logów (chroniona)
├── includes/
│   ├── config.php                ← Konfiguracja (ceny, linki, DB)
│   ├── logger.php                ← System logowania
│   ├── form_handler.php         ← Obsługa formularzy (CSRF, walidacja)
│   ├── header.php                ← Shared header + nav
│   └── footer.php                ← Shared footer
├── pages/
│   ├── marketplace.php           ← /marketplace
│   ├── porownanie.php            ← /porownanie (nowość!)
│   ├── cennik.php                ← /cennik (+ licencjonowanie Premium Hub)
│   ├── dokumentacja.php          ← /dokumentacja
│   ├── api.php                   ← /api
│   ├── kontakt.php               ← /kontakt (z walidacją i logowaniem)
│   ├── regulamin.php             ← /regulamin (DSA/GPSR/Omnibus)
│   └── prywatnosc.php            ← /prywatnosc (RODO)
├── logs/                         ← Logi aplikacji (chronione .htaccess)
│   └── view.php                 ← CLI log viewer
├── assets/
│   ├── css/style.css
│   ├── js/main.js
│   └── img/
├── docker-compose.yml            ← Dev environment
└── docker/
    ├── Dockerfile                ← PHP 8.3 + Apache (jak Plesk)
    └── conf/
        ├── apache-vhost.conf     ← Vhost jak Plesk generuje
        ├── php-custom.ini        ← PHP Settings jak w panelu Plesk
        └── mysql-custom.cnf      ← MySQL 8 defaults
```

## Quick Start (Docker — lokalne dev)

```bash
# Szybki start (SQLite + instalacja)
make start

# Lub krok po kroku:
make install     # Tworzy .env z .env.example
make up          # Uruchom środowisko Docker (SQLite)

# Z MySQL (opcjonalnie):
make up-mysql    # Uruchom z profilem MySQL

# Strona:     http://localhost:8080
# SQLite DB:  ./database/codereview.db
# phpMyAdmin: http://localhost:8081 (tylko z MySQL)
# Mailhog UI:  http://localhost:8025
# Mailhog SMTP: localhost:1026
# Admin Panel: http://localhost:8080/admin/
```

### Logi i Monitoring

Aplikacja posiada wbudowany system logowania w `includes/logger.php`.

```bash
# Podgląd logów (CLI)
./logs/view.php app 50      # Logi aplikacji
./logs/view.php access 20   # Logi dostępu (Apache-like)
./logs/view.php error 100   # Błędy i błędy krytyczne

# Podgląd logów (Web - wymaga auth)
http://localhost:8080/admin/view_logs.php
```

### Makefile commands

```bash
# Quick start
make start       # Instalacja + uruchomienie środowiska (SQLite)

# Development
make install     # Tworzy .env z .env.example + instaluje zależności
make up          # Uruchom środowisko Docker (SQLite)
make up-mysql    # Uruchom z MySQL (opcjonalne)
make down        # Zatrzymaj środowisko
make down-mysql  # Zatrzymaj z MySQL
make logs        # Pokaż logi
make shell       # Shell w kontenerze web
make rebuild     # Przebuduj kontenery

# Database (SQLite)
make db-backup   # Backup bazy SQLite
make db-restore  # Przywracanie bazy SQLite
make db-reset    # Reset bazy SQLite
make db-init     # Inicjalizacja SQLite

# Database (MySQL - opcjonalnie)
make db-backup-mysql   # Backup MySQL
make db-restore-mysql  # Przywracanie MySQL
make db-reset-mysql    # Reset MySQL

# Production
make build-prod  # Stwórz paczkę produkcyjną
make deploy-prod # Instrukcje deployu
make backup-prod # Backup produkcji

# Info
make help        # Pokaż wszystkie komendy
make urls        # Pokaż URL'e deweloperskie
```

## Deploy na Plesk (produkcja)

### 1. Przygotowanie na Plesk

```bash
# W panelu Plesk:
# Websites & Domains → codereview.pl → Hosting Settings
# → Document root: httpdocs
# → PHP version: 8.3
# → PHP handler: FPM application
```

### 2. Upload plików

```bash
# Opcja A: Git (Plesk Git extension)
# Websites → Git → dodaj repo → target: httpdocs/

# Opcja B: FTP/SFTP
# Skopiuj WSZYSTKO do /var/www/vhosts/codereview.pl/httpdocs/
# (bez folderu docker/ i docker-compose.yml)
```

### 3. Ustaw PHP Settings w Plesk

```
Websites → codereview.pl → PHP Settings:
  memory_limit = 512M
  max_execution_time = 300
  upload_max_filesize = 128M
  date.timezone = Europe/Warsaw
  opcache.enable = On
```

### 4. Sprawdź .htaccess

Plesk domyślnie włącza `mod_rewrite`. Jeśli clean URLs nie działają:
```bash
# SSH → sprawdź AllowOverride
grep -r AllowOverride /etc/apache2/
# Powinno być: AllowOverride All
```

### 5. SSL (Plesk auto)

```
Websites → codereview.pl → SSL/TLS Certificates → Let's Encrypt
→ Zaznacz: Redirect HTTP to HTTPS
```

### 6. Baza danych

```
Databases → Add Database:
  Name: codereview
  User: codereview
  → Skopiuj hasło do includes/config.php (lub ustaw env vars)
```

## Pliki do POMINIĘCIA przy deploy na Plesk

```
docker/                 ← tylko dev
docker-compose.yml      ← tylko dev
.dockerignore           ← tylko dev
Makefile               ← tylko dev
.env.example           ← tylko dev
.env                   ← tylko dev (zawiera sekrety!)
README.md               ← opcjonalne
TODO.md                ← opcjonalne
CHANGELOG.md            ← opcjonalne
```

## Konfiguracja

### Plik .env

Kopiuj `.env.example` do `.env` i dostosuj ustawienia:

```bash
make install  # Automatycznie tworzy .env z .env.example
```

Lub ręcznie:
```bash
cp .env.example .env
# Edytuj .env z Twoją konfiguracją
```

### Konfiguracja PHP

Edytuj `includes/config.php`:
- `SITE_*` — linki, email
- `PRICE_*` — ceny marketplace i Premium Hub
- `DB_*` — baza danych (lub użyj env vars z .env)

## Docker services

| Serwis | Port | Opcja | Opis |
|--------|------|-------|------|
| web (Apache+PHP 8.3) | 8080 | Domyślny | Apache + PHP-FPM |
| db (SQLite) | - | Domyślny | Plik `./database/codereview.db` |
| db (MySQL 8) | 3306 | `--profile mysql` | MySQL w panelu DB |
| phpmyadmin | 8081 | `--profile mysql` | phpMyAdmin w Plesk |
| mail (Mailhog UI) | 8025 | Domyślny | Web interface dla maili |
| mail (SMTP) | 1026 | Domyślny | Port SMTP dla aplikacji |

### SQLite vs MySQL

**SQLite (domyślnie):**
- Brak potrzeby konfiguracji bazy danych
- Plik bazy w `./database/codereview.db`
- Szybszy start development
- Idealny na początek

**MySQL (opcjonalnie):**
- `make up-mysql` lub `docker-compose --profile mysql up -d`
- Pełna funkcjonalność SQL
- phpMyAdmin dostępny na porcie 8081
- Lepszy dla większych projektów

## Licencja

Apache License 2.0 — <?= date('Y') ?> Tom Sapletta

## License

Apache License 2.0 - see [LICENSE](LICENSE) for details.

## Author

Created by **Tom Sapletta** - [tom@sapletta.com](mailto:tom@sapletta.com)
