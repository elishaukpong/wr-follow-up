FROM php:8.3-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
        build-essential \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        locales \
        zip \
        unzip \
        git \
        curl \
        libicu-dev \
        libonig-dev \
        libzip-dev \
        libpng-dev \
        libpq-dev \
    && docker-php-ext-install pdo_pgsql zip exif pcntl bcmath intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js for Vite
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

ARG UID=1000
ARG GID=1000

RUN usermod -u ${UID} www-data && groupmod -g ${GID} www-data

USER www-data
