FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    cron

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www

COPY . /var/www

RUN composer install

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

COPY ./docker/cronjob /etc/cron.d/laravel-cron
RUN chmod 0644 /etc/cron.d/laravel-cron
RUN crontab /etc/cron.d/laravel-cron

CMD ["sh", "-c", "php artisan migrate && cron && apache2-foreground"]

EXPOSE 80
