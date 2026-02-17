###############################################
# Plesk-like environment: PHP 8.3 + Apache 2.4
# Mirrors Plesk Obsidian on Ubuntu 22.04/24.04
#
# Plesk uses: Apache as reverse proxy → PHP-FPM
# Here we use mod_php for simplicity, same result
###############################################

FROM php:8.3-apache

LABEL maintainer="tom@sapletta.com"
LABEL description="CodeReview.pl — Plesk-like dev environment"

# ─── System packages (what Plesk installs) ─────
RUN apt-get update && apt-get install -y --no-install-recommends \
    # Build deps
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libzip-dev libicu-dev libxml2-dev libonig-dev \
    libcurl4-openssl-dev libssl-dev \
    # Tools available on Plesk
    unzip curl git vim nano wget \
    # Mail
    msmtp msmtp-mta \
    # Clean up
    && rm -rf /var/lib/apt/lists/*

# ─── PHP extensions (Plesk's default PHP 8.3 set) ─────
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        mysqli \
        pdo \
        pdo_mysql \
        zip \
        intl \
        mbstring \
        xml \
        curl \
        opcache \
        bcmath \
        exif \
        fileinfo

# ─── Enable Apache modules (Plesk defaults) ─────
RUN a2enmod \
    rewrite \
    headers \
    ssl \
    expires \
    deflate \
    proxy \
    proxy_fcgi \
    env

# ─── Composer (Plesk installs it globally) ─────
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ─── Plesk-like directory structure ─────
# Plesk stores sites in /var/www/vhosts/<domain>/httpdocs/
RUN mkdir -p \
    /var/www/vhosts/codereview.pl/httpdocs \
    /var/www/vhosts/system/codereview.pl/logs \
    && chown -R www-data:www-data /var/www/vhosts

# ─── Mail config (route to mailhog in dev) ─────
RUN echo 'account default\nhost mail\nport 1025\nfrom noreply@codereview.pl\n' > /etc/msmtprc

# ─── Apache env vars ─────
ENV APACHE_DOCUMENT_ROOT=/var/www/vhosts/codereview.pl/httpdocs
ENV APACHE_LOG_DIR=/var/www/vhosts/system/codereview.pl/logs
ENV APACHE_RUN_USER=www-data
ENV APACHE_RUN_GROUP=www-data

# ─── Set document root ─────
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf

WORKDIR /var/www/vhosts/codereview.pl/httpdocs

EXPOSE 80 443
