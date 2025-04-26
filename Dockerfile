FROM php:8.1-apache

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql pdo_pgsql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first for better caching
COPY composer.json composer.lock* ./

# Install Heroku PHP buildpack
RUN mkdir -p /app/.heroku/php/bin
RUN curl https://lang-php.s3.amazonaws.com/dist-heroku-20-stable/php-8.1.5.tar.gz | tar xz -C /app/.heroku/php
RUN curl https://lang-php.s3.amazonaws.com/dist-heroku-20-stable/composer-2.3.5.tar.gz | tar xz -C /app/.heroku/php/bin
RUN curl https://lang-php.s3.amazonaws.com/dist-heroku-20-stable/nginx-1.20.2.tar.gz | tar xz -C /app/.heroku
RUN curl https://lang-php.s3.amazonaws.com/dist-heroku-20-stable/apache-2.4.53.tar.gz | tar xz -C /app/.heroku
RUN cp -a /app/.heroku/php/bin/composer /usr/local/bin/composer
RUN PATH=/app/.heroku/php/bin:/app/.heroku/apache/bin:$PATH

# Run composer install
RUN composer install --no-dev --optimize-autoloader
# Copy application files
COPY . .
# Configure Apache document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]