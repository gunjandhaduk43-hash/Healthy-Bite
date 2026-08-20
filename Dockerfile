FROM php:8.2-apache

# Install PDO MySQL and required extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable Apache mod_rewrite for router & .htaccess
RUN a2enmod rewrite

# Update Apache document root to point to /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Create and grant permissions for storage and public directories
RUN mkdir -p /var/www/html/storage/logs /var/www/html/public/uploads /var/www/html/public/qr \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/public/uploads /var/www/html/public/qr \
    && chmod -R 775 /var/www/html/storage /var/www/html/public/uploads /var/www/html/public/qr

EXPOSE 80

CMD ["apache2-foreground"]
