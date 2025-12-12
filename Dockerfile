# ===============================================
# ETAPA 1: BUILDER (Instala dependencias)
# ... (dependencias y RUN apk add)

# Establecer el directorio de trabajo
WORKDIR /app

# Copiar archivos esenciales para Composer (para no invalidar el caché)
COPY composer.json composer.lock ./

# --- COMIENZA LA CORRECCIÓN CLAVE ---
# Copiar el resto de la aplicación (esto asegura que 'artisan' esté presente)
# Lo movemos AQUI para que Composer lo encuentre.
COPY . .
# --- FIN DE LA CORRECCIÓN CLAVE ---


# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar dependencias de Laravel
# AHORA Composer encontrará 'artisan' cuando ejecute sus scripts post-instalación
RUN composer install --no-dev --prefer-dist --optimize-autoloader

# Generar la clave de la aplicación (si no se hace por env var)
# RUN php artisan key:generate

# Configurar permisos
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && chmod -R 775 /app/storage /app/bootstrap/cache

# ... (resto del Dockerfile)