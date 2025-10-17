# ----------------------------------------
#  Base Image: PHP 8.4 FPM (Alpine)
# ----------------------------------------
FROM php:8.4-fpm-alpine

# Pastikan environment tidak interaktif
ENV COMPOSER_ALLOW_SUPERUSER=1 \
    PATH="$PATH:/var/www/html/vendor/bin"

# ----------------------------------------
#  Install system dependencies
# ----------------------------------------
RUN apk update && apk upgrade && \
    apk add --no-cache \
        git \
        curl \
        zip \
        unzip \
        libzip-dev \
        icu-dev \
        oniguruma-dev \
        libxml2-dev \
        gmp-dev \
        freetype-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        bash \
        shadow

# ----------------------------------------
#  Install PHP extensions
# ----------------------------------------
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        intl \
        zip \
        opcache

# ----------------------------------------
#  Install Composer
# ----------------------------------------
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

# ----------------------------------------
#  Set working directory
# ----------------------------------------
WORKDIR /var/www/html

# ----------------------------------------
#  Create user dengan UID 1000 (sama seperti host)
# ----------------------------------------
RUN adduser -D -s /bin/bash -u 1000 -G www-data www-user

# ----------------------------------------
#  Copy project files dengan ownership yang benar
# ----------------------------------------
COPY --chown=www-user:www-data . .

# ----------------------------------------
#  Fix permissions SELAMA BUILD (sebagai root)
# ----------------------------------------
RUN mkdir -p /var/www/html/storage && \
    chown -R www-user:www-data /var/www/html && \
    chmod -R 775 /var/www/html/storage

# ----------------------------------------
#  Install dependencies sebagai www-user
# ----------------------------------------
USER www-user
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# ----------------------------------------
#  Konfigurasi PHP-FPM untuk menggunakan user yang benar
# ----------------------------------------
USER root
RUN echo "user = www-user" >> /usr/local/etc/php-fpm.d/www.conf && \
    echo "group = www-data" >> /usr/local/etc/php-fpm.d/www.conf && \
    echo "listen.owner = www-user" >> /usr/local/etc/php-fpm.d/www.conf && \
    echo "listen.group = www-data" >> /usr/local/etc/php-fpm.d/www.conf

# ----------------------------------------
#  Final permission check sebelum runtime
# ----------------------------------------
RUN ls -la /var/www/html/storage/ && \
    ls -la /var/www/html/bootstrap/

# ----------------------------------------
#  Switch ke user non-root untuk runtime
# ----------------------------------------
USER www-user

# ----------------------------------------
#  Expose PHP-FPM port
# ----------------------------------------
EXPOSE 9000

# ----------------------------------------
#  Start PHP-FPM
# ----------------------------------------
CMD ["php-fpm"]