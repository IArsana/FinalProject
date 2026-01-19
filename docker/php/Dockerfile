FROM php:8.2-apache

# ==========================
# APACHE CONFIG
# ==========================

# Enable rewrite module
RUN a2enmod rewrite

# Allow .htaccess override
RUN sed -i 's/AllowOverride None/AllowOverride All/g' \
    /etc/apache2/apache2.conf

# ==========================
# SYSTEM DEPENDENCIES
# ==========================
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    && rm -rf /var/lib/apt/lists/*

# ==========================
# PHP EXTENSIONS
# ==========================
RUN docker-php-ext-install pdo pdo_mysql

# ==========================
# INSTALL COMPOSER
# ==========================
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ==========================
# WORKDIR
# ==========================
WORKDIR /var/www/html

# Copy project
COPY . /var/www/html

# Permission
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80