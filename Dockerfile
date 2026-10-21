
# Étape 1 : compiler les ressources front-end
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build


# Étape 2 : préparer l'application Laravel pour la production
FROM php:8.3-fpm-alpine AS backend

RUN apk add --no-cache \
    nginx \
    supervisor \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    zip \
    unzip \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_pgsql \
        gd \
        zip \
        bcmath \
        mbstring

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Installer uniquement les dépendances de production
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist

# Copier les ressources front-end compilées
COPY --from=frontend /app/public/build /var/www/html/public/build

# Préparer les répertoires et permissions Laravel
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/start.sh /start.sh

RUN chmod +x /start.sh

EXPOSE 10000

CMD ["/start.sh"]


# Étape 3 : image dédiée aux opérations de seeding
# Cette image inclut les dépendances de développement, dont Faker.
FROM backend AS seeder

RUN composer install \
    --optimize-autoloader \
    --no-interaction \
    --prefer-dist
