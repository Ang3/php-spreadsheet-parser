FROM php:8.1-cli-alpine

WORKDIR /app

# Install system packages for compilation
RUN apk add --no-cache \
    bash \
    git \
    unzip \
    curl \
    autoconf \
    make \
    gcc \
    g++ \
    musl-dev \
    icu-dev \
    zlib-dev \
    libzip-dev \
    oniguruma-dev \
    linux-headers \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev

# Install PHP extensions
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
 && docker-php-ext-install intl zip gd

# Install PCOV
RUN pecl install pcov \
    && docker-php-ext-enable pcov \
    && echo "pcov.enabled=1" >> /usr/local/etc/php/conf.d/pcov.ini \
    && echo "pcov.directory=/app/src" >> /usr/local/etc/php/conf.d/pcov.ini

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Docker entrypoint
COPY --link --chmod=755 docker/entrypoint.sh /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
CMD ["php", "-a"]