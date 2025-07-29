FROM php:8.4-cli

ARG UID
ARG GID

RUN addgroup --gid ${GID} app \
    && adduser --disabled-password --gecos "" --uid ${UID} --gid ${GID} app

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev \
    && docker-php-ext-install pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

USER app
WORKDIR /var/www/html

CMD ["php", "-S", "0.0.0.0:80", "-t", "public"]
