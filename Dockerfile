# ===============================================
# ETAPA 1: BUILDER (Instala dependencias)
# Usa la imagen oficial de PHP con FPM para el servidor
FROM php:8.3-fpm-alpine AS builder

# Instalar dependencias del sistema necesarias
RUN apk add --no-cache \
    git \
    mysql-client \
    unzip \
    libzip-dev \
    libpng-dev \
    icu-dev \
    # Dependencias de composer
    && docker-php-ext-install pdo pdo_mysql zip intl opcache bcmath

# Establecer el directorio de trabajo
WORKDIR /app

# Copiar archivos esenciales para Composer (para no invalidar el caché)
COPY composer.json composer.lock ./

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar dependencias de Laravel
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Copiar el resto de la aplicación
COPY . .

# Generar la clave de la aplicación (solo si es necesario, si no, usa una variable de entorno)
# RUN php artisan key:generate

# Configurar permisos
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache


# ===============================================
# ETAPA 2: PRODUCTION (Imagen final ligera)
# Usar la misma base para el entorno de ejecución
FROM php:8.3-fpm-alpine AS production

# Copiar los archivos de la aplicación desde la etapa builder
COPY --from=builder /app /app

# Copiar la configuración del servidor web (NGINX)
# Heroku necesita un archivo Procfile para Nginx o Caddy, o usarás solo PHP FPM
# Para una API simple, puedes ejecutar solo el servidor de desarrollo de PHP o FPM+Nginx

# Configurar el directorio de trabajo
WORKDIR /app

# Exponer el puerto por defecto de FPM
EXPOSE 9000

# Comando por defecto para iniciar PHP-FPM
CMD ["php-fpm"]