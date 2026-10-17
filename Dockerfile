# Multi-stage Dockerfile for Laravel 10 (PHP 8.2)

# Stage 1: Build Frontend Assets with Node
FROM node:20-alpine AS node_builder

WORKDIR /app

# Copy dependency definition files
COPY package.json package-lock.json ./

# Install npm dependencies
RUN npm ci

# Copy project files needed for asset compilation
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public

# Build production assets (Vite)
RUN npm run build

# Stage 2: Production PHP + Nginx Environment
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install system dependencies & PHP extension build dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    zip \
    unzip \
    netcat-openbsd \
    oniguruma-dev \
    icu-dev

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl \
    opcache

# Install latest Composer from official image
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application source code
COPY . /var/www/html

# Copy compiled frontend assets from Stage 1
COPY --from=node_builder /app/public/build /var/www/html/public/build

# Install PHP dependencies via Composer (optimized for production)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copy Nginx, Supervisor, and Entrypoint configurations
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Fix permissions and line endings
RUN chmod +x /usr/local/bin/entrypoint.sh \
    && sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose web port 80
EXPOSE 80

# Define entrypoint and default command
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
