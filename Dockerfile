FROM php:8.2-apache

# Installer les extensions nécessaires
RUN docker-php-ext-install pdo pdo_mysql

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers Symfony
WORKDIR /var/www/html
COPY . .

# Installer les dépendances Symfony
RUN composer install --no-dev --optimize-autoloader

# Donner les bons droits aux dossiers de cache et logs
RUN chown -R www-data:www-data /var/www/html/var

# Exposer le port Apache
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]
