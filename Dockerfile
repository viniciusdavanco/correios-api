FROM php:8.2-apache

# enable apache rewrite module
RUN a2enmod rewrite

# install composer inside container
RUN apt-get update && apt-get install -y unzip git \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# copy app
WORKDIR /var/www/html
COPY . /var/www/html

# install composer packages
RUN composer install --no-dev --optimize-autoloader

# expose port used by Render (MUITO IMPORTANTE)
EXPOSE 10000

# Apache listens on 80, Render maps 10000 -> 80
CMD ["apache2-foreground"]
