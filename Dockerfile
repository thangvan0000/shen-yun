FROM php:8.4-cli

WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
        libpq-dev \
        nodejs \
        npm \
        git \
        unzip \
        libzip-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
    && docker-php-ext-install pdo pdo_pgsql zip mbstring exif pcntl bcmath gd \
    && curl -sS https://getcomposer.org/installer \
        | php -- --install-dir=/usr/local/bin --filename=composer

# Copy application files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node dependencies and build assets
RUN npm install && npm run build

EXPOSE $PORT

# Make startup script executable
RUN chmod +x /var/www/html/startup.sh

CMD ["/var/www/html/startup.sh"]
