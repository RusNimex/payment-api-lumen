FROM php:8.1-cli

# system deps
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && docker-php-ext-install zip \
    && rm -rf /var/lib/apt/lists/*

# php-redis
RUN pecl install redis \
    && docker-php-ext-enable redis

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app