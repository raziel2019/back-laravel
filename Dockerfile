# Usa PHP 8.3 con Apache
FROM php:8.3-apache

# Instalar extensiones necesarias para Laravel y PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_pgsql

# Habilitar mod_rewrite para Apache
RUN a2enmod rewrite

# Copiar los archivos del proyecto
COPY . /var/www/html

# Establecer permisos adecuados
RUN chown -R www-data:www-data /var/www/html

# Definir directorio de trabajo
WORKDIR /var/www/html

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --no-interaction

# Exponer el puerto 80 para Apache
EXPOSE 80
