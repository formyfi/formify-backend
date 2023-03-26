# Use an official Apache runtime as a parent image
FROM php:8-apache

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the current directory contents into the container at /var/www/html
COPY . /var/www/html

# Install any needed packages
RUN apt-get update && \
    apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install dependencies
RUN composer install

# Install dependencies
RUN php artisan db:seed

RUN php artisan key:generate

RUN php artisan storage:link

RUN chmod o+w ./storage/ -R

# Enable mod_rewrite
RUN a2enmod rewrite

# Expose port 80 and start Apache server
EXPOSE 80
CMD ["apache2-foreground"]