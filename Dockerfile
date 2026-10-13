FROM dunglas/frankenphp:1-php8.2

# Install extensions na composer
RUN install-php-extensions gd pdo_pgsql pgsql
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-scripts

RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache && chmod -R 777 storage bootstrap/cache

# Install node na build
RUN apt-get update && apt-get install -y nodejs npm && npm install && npm run build

EXPOSE 80
