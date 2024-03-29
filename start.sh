#/bin/bash'


echo "Nyalakan PHP FPM"
service php8.1-fpm start
echo "Status php-fpm"
service php8.1-fpm status

echo "Nyalakan Nginx"
service nginx start

echo "Status nginx"
service nginx  status

composer install 

composer require  alurkerja-laravolt/crud:dev-master -W -n

php artisan storage:link

php artisan laravolt:link

php artisan optimize:clear

php artisan migrate

php artisan scribe:generate

chown -R www-data:www-data /var/www/project/storage

echo "Watch Laravel Logs "
echo "" >  /var/www/project/storage/logs/laravel.log

chown -R www-data:www-data /var/www/project/storage/logs/laravel.log

tail -f  /var/www/project/storage/logs/laravel.log
# nginx -g "daemon off;"

# ["nginx", "-g", "daemon off;"]
