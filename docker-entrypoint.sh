#!/bin/bash
chown -R www-data:www-data /var/www/html/storage /var/www/html/public
chmod -R 775 /var/www/html/storage
chmod -R 755 /var/www/html/public

# Uruchomienie głównego procesu (np. PHP-FPM)
exec "$@"
