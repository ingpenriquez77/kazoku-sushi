# Usamos PHP 8.4 con Apache para Laravel
FROM php:8.4-apache

# 1. Instalar dependencias del sistema operativo y herramientas de compilación
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

# 2. Instalar extensión de MongoDB via PECL y extensiones básicas de PHP
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install mbstring exif pcntl bcmath gd

# 3. Habilitar mod_rewrite de Apache para las rutas de Laravel
RUN a2enmod rewrite

# 4. Apuntar la raíz web de Apache a /public y permitir que lea el .htaccess
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

RUN echo "<Directory /var/www/html/public>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>" >> /etc/apache2/apache2.conf

# 5. Configurar Apache para escuchar en el puerto dinámico de Render ($PORT)
ENV PORT 80
RUN sed -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g' /etc/apache2/sites-available/000-default.conf

# 6. Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 7. Copiar los archivos del proyecto al contenedor
WORKDIR /var/www/html
COPY . /var/www/html

# 8. Instalar dependencias de PHP para producción
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 9. Dar permisos de escritura a las carpetas requeridas por Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Puerto por defecto expuesto
EXPOSE 80

# 11. Limpieza de caché, migración de índices NoSQL, carga de seeders y arranque de Apache
CMD ["sh", "-c", "php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear && php artisan migrate --force && php artisan db:seed --force && apache2-foreground"]
