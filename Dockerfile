# Use the official PHP image with version 8.2 and Apache
FROM php:8.2-apache

# Install required PHP extensions (e.g., mysqli, pdo_mysql)
RUN docker-php-ext-install mysqli pdo_mysql

# Copy application files into the container
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html/

# Expose port 80
EXPOSE 80
