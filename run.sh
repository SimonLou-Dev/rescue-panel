#!/bin/sh
echo 'starting app ... '
cd /var/www

echo 'caching data ... '
php artisan cache:clear
php artisan storage:link
php artisan key:generate
php artisan schedule:run

echo 'building front ...'
yarn sass ./resources/sass/app.scss ./public/css/app.css
yarn build

echo 'start worker & nginx... '
pm2 start ./queueworker.yml
nginx -t && service nginx restart

echo 'launch '
/usr/local/sbin/php-fpm -R
