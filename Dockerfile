# Usar imagen oficial de PHP con Apache
FROM php:8.2-apache

# Copiar todos los archivos del proyecto al servidor web de Apache
COPY . /var/www/html/

# Habilitar módulos necesarios
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Dar permisos adecuados
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Exponer el puerto 80
EXPOSE 18012
