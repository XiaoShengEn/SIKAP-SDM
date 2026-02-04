# ---------- Stage 1: Build frontend (Vite) ----------
FROM node:20-alpine AS nodebuilder

WORKDIR /app
COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build


# ---------- Stage 2: PHP-FPM ----------
FROM php:8.2-fpm-alpine

# System deps + phpize deps
RUN apk add --no-cache \
    git curl zip unzip \
    ca-certificates \
    libpng-dev oniguruma-dev libxml2-dev libzip-dev \
    $PHPIZE_DEPS

RUN update-ca-certificates

# PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Redis
RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy composer dulu (cache layer)
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --optimize-autoloader

# Copy project
COPY . .

# Copy hasil build Vite
COPY --from=nodebuilder /app/public/build /var/www/html/public/build
# Keep a copy outside /public so we can sync into the public volume at runtime
COPY --from=nodebuilder /app/public/build /opt/public_build
RUN rm -f /var/www/html/public/hot

# Copy PHP config
COPY docker/php/local.ini /usr/local/etc/php/conf.d/local.ini

RUN chown -R www-data:www-data storage bootstrap/cache

# Entrypoint to remove Vite dev "hot" file in production containers
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["sh", "/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm", "-F"]
