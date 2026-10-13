FROM dunglas/frankenphp:1-php8.2

RUN install-php-extensions gd pdo_pgsql pgsql zip

RUN apt-get update && apt-get install -y unzip git nodejs npm

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-scripts

RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache && chmod -R 777 storage bootstrap/cache

RUN npm install && npm run build

EXPOSE 80
