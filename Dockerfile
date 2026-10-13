# v6 - fix redirect loop
FROM dunglas/frankenphp:1-php8.2

ENV SERVER_NAME=:80
ENV CADDY_GLOBAL_OPTIONS="{ admin off }"

RUN install-php-extensions gd pdo_pgsql pgsql zip bcmath intl
RUN apt-get update && apt-get install -y unzip git nodejs npm
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Hakikisha Caddyfile yetu ndiyo inatumika
COPY Caddyfile /etc/caddy/Caddyfile

RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction
RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache && chmod -R 777 storage bootstrap/cache
RUN npm install || true
RUN npm run build || true

EXPOSE 80
