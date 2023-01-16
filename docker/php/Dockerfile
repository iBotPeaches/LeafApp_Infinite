FROM php:8.1-fpm

# Setup node repo
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -

# Install system dependencies
#  * git zip unzip - Required by Composer
#  * mariadb-client - Required for db migration
#  * nodejs - Required for building js
RUN apt update && apt install -y git zip unzip mariadb-client nodejs

# Clear apt cache
RUN apt clean && rm -rf /var/lib/apt/lists/*

# Install yarn
RUN npm install -g yarn

# Install PHP extensions
#  * bcmath pdo_mysql - Required by laravel/framework
#  * pcntl - Required by laravel/horizon
#  * exif - Required by spatie/image
RUN docker-php-ext-install bcmath pdo_mysql pcntl exif

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
