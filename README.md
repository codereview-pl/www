# CodeReview.pl / coboarding.com — Website

Strona PHP zoptymalizowana pod **Plesk Obsidian** z Docker dev environment.

## Struktura (Plesk-compatible)

```
httpdocs/                         ← Plesk document root
├── index.php                     ← STRONA GŁÓWNA (w katalogu głównym!)
├── .htaccess                     ← Clean URLs + security
├── includes/
│   ├── config.php                ← Konfiguracja (ceny, linki, DB)
│   ├── header.php                ← Shared header + nav
│   └── footer.php                ← Shared footer
├── pages/
│   ├── marketplace.php           ← /marketplace
│   ├── cennik.php                ← /cennik (+ licencjonowanie Premium Hub)
│   ├── dokumentacja.php          ← /dokumentacja
│   ├── api.php                   ← /api
│   ├── kontakt.php               ← /kontakt
│   ├── regulamin.php             ← /regulamin (DSA/GPSR/Omnibus)
│   └── prywatnosc.php            ← /prywatnosc (RODO)
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
# Uruchom środowisko identyczne z Plesk
docker-compose up -d

# Strona:     http://localhost:8080
# phpMyAdmin: http://localhost:8081
# Mailhog:    http://localhost:8025
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
README.md               ← opcjonalne
```

## Konfiguracja

Edytuj `includes/config.php`:
- `SITE_*` — linki, email
- `PRICE_*` — ceny marketplace i Premium Hub
- `DB_*` — baza danych (lub env vars)

## Docker services

| Serwis | Port | Odpowiednik Plesk |
|--------|------|-------------------|
| web (Apache+PHP 8.3) | 8080 | Apache + PHP-FPM |
| db (MySQL 8) | 3306 | MySQL w panelu DB |
| phpmyadmin | 8081 | phpMyAdmin w Plesk |
| mail (Mailhog) | 8025 | Plesk Mail |

## Licencja

Apache License 2.0 — <?= date('Y') ?> Tom Sapletta

## License

Apache License 2.0 - see [LICENSE](LICENSE) for details.

## Author

Created by **Tom Sapletta** - [tom@sapletta.com](mailto:tom@sapletta.com)
