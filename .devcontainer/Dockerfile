# Dev container for Laravel + Vite
FROM mcr.microsoft.com/devcontainers/php:1-8.3-bullseye

# Install system deps
RUN apt-get update && apt-get install -y     git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev     default-mysql-client     && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install pdo_mysql zip gd

# Composer already included in this image; ensure it's latest
RUN composer self-update || true

# Node is added by features, but ensure pnpm/yarn optional
RUN npm i -g pnpm yarn

# Xdebug (optional for debugging)
RUN pecl install xdebug && docker-php-ext-enable xdebug || true

# Configure git safe directory
RUN git config --system --add safe.directory /workspace
