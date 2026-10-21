
#!/bin/sh
set -e

# Préparer le cache de configuration Laravel
php artisan config:cache

# Démarrer PHP-FPM et Nginx
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
