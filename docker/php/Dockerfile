FROM php:8.4-fpm

ARG HOST_UID
RUN usermod -u $HOST_UID www-data

# Setup node repo
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -

# Install system dependencies
#  * git zip unzip - Required by Composer
#  * mariadb-client - Required for db migration
#  * nodejs - Required for building js
#  * libicu-dev - Required for php int ext
RUN apt update && apt install -y git zip unzip mariadb-client nodejs libicu-dev

# Clear apt cache
RUN apt clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
#  * bcmath pdo_mysql intl - Required by laravel/framework
#  * pcntl - Required by laravel/horizon
#  * exif - Required by spatie/image
RUN docker-php-ext-install bcmath pdo_mysql intl pcntl exif

# Install Xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
