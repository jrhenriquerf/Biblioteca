FROM php:7.3-fpm

# Copy composer.lock and composer.json
COPY ./src/composer.lock ./src/composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring

# Install Postgre PDO
RUN apt-get update && apt-get install -y libpq-dev \
	&& docker-php-ext-install pdo_pgsql

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY ./src /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
