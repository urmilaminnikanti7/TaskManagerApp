# Use official PHP 8.4 FPM image
FROM php:8.4-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    wget \
    gnupg \
    apt-transport-https \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && docker-php-ext-install \
        intl \
        pdo_mysql \
        pdo_pgsql \
        opcache \
        zip \
        bcmath \
        mbstring \
        xml

# Enable recommended PHP extensions
RUN docker-php-ext-enable opcache

# Install Composer (latest)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Symfony CLI (standalone)
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony


WORKDIR /var/www/symfony

EXPOSE 9000
CMD ["php-fpm"]
