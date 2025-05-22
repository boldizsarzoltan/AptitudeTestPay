FROM php:8.3-cli-alpine
WORKDIR /app
COPY composer.json  ./
#COPY composer.lock ./
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN composer install --no-dev --no-scripts --no-interaction
COPY ./src ./src
COPY ./script.php ./script.php
CMD ["php", "script.php"]