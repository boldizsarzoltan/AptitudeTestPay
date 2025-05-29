FROM php:7.4-cli-alpine
WORKDIR /app
COPY composer.json  ./
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN docker-php-ext-install bcmath
RUN composer install --no-dev --no-scripts --no-interaction
COPY ./src ./src
COPY ./script.php ./script.php
COPY ./.env ./.env
COPY ./input.csv ./input.csv
CMD ["php", "script.php"]