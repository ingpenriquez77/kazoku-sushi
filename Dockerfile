# 1. Usamos PHP 8.4 con Apache como base
FROM php:8.4-apache

# 2. Instalar dependencias del sistema operativo para MongoDB, Zip y SSL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    pkg-config \
    zip \
    unzip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 3. Instalar la extensión de MongoDB vía PECL y extensiones requeridas por Laravel
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install mbstring exif pcntl bcmath gd

# 4. Habilitar mod_rewrite de Apache para el enrutamiento de Laravel
RUN a2enmod rewrite

# 5. Configurar el DocumentRoot apuntando a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

RUN echo "<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

# 6. Configurar el puerto dinámico para Render ($PORT)
ENV PORT 80
RUN sed -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g' /etc/apache2/sites-available/000-default.conf

# 7. Copiar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 8. Establecer directorio de trabajo y copiar el proyecto
WORKDIR /var/www/html
COPY . /var/www/html

# 9. Instalar dependencias de producción (omite dependencias de desarrollo)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 10. Crear carpetas necesarias y asegurar permisos de Apache
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 11. Exponer el puerto
EXPOSE 80

# 12. Script de arranque: limpia cachés, corre migraciones y seeders, y enciende Apache
CMD ["sh", "-c", "php artisan config:clear && php artisan cache:clear && php artisan view:clear && php artisan migrate --force && php artisan db:seed --force && apache2-foreground"]
