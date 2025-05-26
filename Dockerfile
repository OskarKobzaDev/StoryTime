FROM php:8.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libzip4 \
    nodejs \
    npm \
    autoconf \
    make \
    gcc \
    g++ \
    libc6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get purge -y --auto-remove autoconf make gcc g++ libc6-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN echo "expose_php = Off" > /usr/local/etc/php/conf.d/security.ini

WORKDIR /var/www/StoryTime

RUN chown -R www-data:www-data /var/www/StoryTime

EXPOSE 9000

CMD ["php-fpm"]