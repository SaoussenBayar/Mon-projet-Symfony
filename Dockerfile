# Utiliser une image PHP avec FPM
FROM php:8.3-fpm

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    default-mysql-client\
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    nginx \
    libmysqlclient-dev && \  
    docker-php-ext-configure gd --with-freetype --with-jpeg && \  
    docker-php-ext-configure pdo_mysql && docker-php-ext-install pdo pdo_mysql

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Installer Symfony CLI (optionnel)
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier les fichiers de l'application
COPY . /var/www/html/

# Installer les dépendances Composer
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Configurer les permissions
RUN chown -R www-data:www-data /var/www/html/var

# Configurer Nginx
COPY ./nginx/default.conf /etc/nginx/sites-available/default

# Exposer le port 80 pour Nginx
EXPOSE 80

# Démarrer Nginx et PHP-FPM
CMD service nginx start && php-fpm
