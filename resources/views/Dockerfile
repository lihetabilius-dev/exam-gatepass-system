FROM dunglas/frankenphp:1-php8.2

RUN install-php-extensions gd

COPY . /app
WORKDIR /app

RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache && chmod -R 777 storage bootstrap/cache

EXPOSE 80
