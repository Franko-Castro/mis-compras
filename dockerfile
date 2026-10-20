FROM php:8.2-apache

# Extensiones PHP necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Módulos de Apache: reescritura, cabeceras, compresión y caducidad
RUN a2enmod rewrite headers deflate expires

# ------------------------------------------------------------------
# OPcache: compilación en caché de los scripts PHP (gran mejora en TTFB)
# ------------------------------------------------------------------
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.enable_cli=0'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.revalidate_freq=60'; \
    echo 'opcache.validate_timestamps=1'; \
    echo 'opcache.fast_shutdown=1'; \
} > /usr/local/etc/php/conf.d/opcache-recommended.ini

COPY . /var/www/html/

EXPOSE 80
