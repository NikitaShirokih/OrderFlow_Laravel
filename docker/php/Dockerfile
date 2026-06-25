FROM php:8.4-cli-bookworm

ARG UID=1000
ARG GID=1000

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        curl \
        unzip \
        libpq-dev \
        libicu-dev \
        libzip-dev \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_pgsql \
        bcmath \
        intl \
        pcntl \
        sockets \
        zip \
        opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN groupadd --gid "${GID}" app \
    && useradd --uid "${UID}" --gid app --create-home --shell /bin/bash app

WORKDIR /var/www/app

COPY docker/php/php.ini /usr/local/etc/php/conf.d/app.ini

USER app

EXPOSE 8000

CMD ["php", "artisan", "octane:start", "--server=roadrunner", "--host=0.0.0.0", "--port=8000", "--workers=2", "--task-workers=2", "--max-requests=500"]