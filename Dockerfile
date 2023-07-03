FROM php:8.1.0-apache

# Establecer directorio de trabajo
WORKDIR /var/www/html

# Habilitar módulo de reescritura
RUN a2enmod rewrite

# Linux Library
RUN apt-get update -y && apt-get install -y libicu-dev unzip zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar controladores de PHP para MySQL
RUN docker-php-ext-install pdo_mysql