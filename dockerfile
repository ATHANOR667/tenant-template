# --- Étape 1 : Construction de l'application ---
FROM composer:2 AS composer_stage
WORKDIR /app

# Installe les dépendances nécessaires pour l'extension GD + pcntl
RUN apk add --no-cache libjpeg-turbo-dev libpng-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pcntl \
    && rm -rf /var/cache/apk/*

# Copie l'ensemble des fichiers de votre projet dans le conteneur
COPY . /app

# Installe les dépendances PHP (pcntl est présent, donc horizon ne bloque plus)
RUN composer install --no-dev --optimize-autoloader


# --- Étape 2 : Image finale avec PHP-FPM ---
FROM php:8.2-fpm-alpine
WORKDIR /var/www/html

# Installe les dépendances et extensions (PostgreSQL, Node/NPM, Redis, Supervisor, et GD + pcntl)
RUN for i in 1 2 3 4 5; do \
    apk add --no-cache \
        libjpeg-turbo-dev libpng-dev \
        postgresql-dev \
        supervisor \
        nodejs npm \
        redis lz4-dev autoconf make \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_pgsql pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && break || sleep 5; \
done \
&& rm -rf /var/cache/apk/*

# Crée le dossier de log requis par Supervisor et donne les permissions
RUN mkdir -p /var/log/supervisor && chown www-data:www-data /var/log/supervisor

# Copie des fichiers de l'étape composer
COPY --from=composer_stage /app .

# Crée le dossier de configuration Supervisor s'il n'existe pas
RUN mkdir -p /etc/supervisor/conf.d

# Copie des fichiers de configuration Supervisor
COPY .docker/supervisor/supervisord.conf /etc/supervisord.conf
COPY .docker/supervisor/php-fpm.conf /etc/supervisor/conf.d/php-fpm.conf
COPY .docker/supervisor/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf
COPY .docker/supervisor/laravel-pulse.conf /etc/supervisor/conf.d/laravel-pulse.conf
COPY .docker/supervisor/laravel-horizon.conf /etc/supervisor/conf.d/laravel-horizon.conf
COPY .docker/supervisor/laravel-scheduler.conf /etc/supervisor/conf.d/laravel-scheduler.conf

# Copie la configuration PHP-FPM pour corriger l'erreur de permission de log
COPY .docker/php/www.conf /usr/local/etc/php-fpm.d/zz-www.conf

# Donne les bonnes permissions à l'utilisateur web (www-data).
RUN chown -R www-data:www-data /var/www/html

# Expose le port 9000 pour que le serveur web (Nginx) puisse communiquer avec PHP.
EXPOSE 9000

# Commande finale : démarre Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
