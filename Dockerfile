# Utiliser une image PHP avec FPM
FROM php:8.3-fpm

# Installer les dépendances système et PHP
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    mariadb-client \
    libmariadb-dev \
    libmariadb-dev-compat \
    && docker-php-ext-install pdo_mysql
# Nettoyage des fichiers inutiles pour réduire la taille du conteneur
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Vérification de pdo_mysql après installation
RUN php -m | grep pdo

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

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000

# Lancer PHP-FPM
CMD ["php-fpm"]
