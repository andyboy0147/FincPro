FROM php:5.4-apache

# Enable Apache Rewrite Module
RUN docker-php-ext-install mysqli pdo_mysql

WORKDIR /var/www/html

# Copy project files
COPY . .

# Expose port
EXPOSE 80

CMD ["apache2-foreground"]
