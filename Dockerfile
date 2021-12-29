FROM php:7.4-fpm


# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libwebp-dev libjpeg62-turbo-dev libpng-dev libxpm-dev \
    libfreetype6 \
    libfreetype6-dev \
    locales \
    zip \
    libonig-dev \
    vim \
    unzip \
    git \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring  pcntl
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer


# Copy existing application directory contents
COPY . /var/www



# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]