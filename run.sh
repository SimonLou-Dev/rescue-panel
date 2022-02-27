#!/bin/sh
echo 'starting app ... '
cd /var/www

echo 'caching data ... '
php artisan cache:clear
php artisan route:cache
php artisan event:cache

echo 'start worker & nginx... '
pm2 start ./queueworker.yml
nginx -t && nginx -s reload

echo 'launch '
/usr/local/sbin/php-fpm -R
