# Base image
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    curl \
    && docker-php-ext-install pdo pdo_mysql zip

RUN docker-php-ext-install pcntl
RUN docker-php-ext-configure pcntl --enable-pcntl

# Install Node.js and npm
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy Laravel app files first (including artisan)
COPY . .

# Ensure storage & bootstrap/cache have correct permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Now install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install frontend dependencies
RUN npm install && npm run build

# Expose necessary ports
EXPOSE 80 8080

COPY start.sh start.sh
RUN chmod +x start.sh

CMD ["./start.sh"]
