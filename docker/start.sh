#!/bin/bash

# Set the timezone. Base image does not contain the setup-timezone script, so an alternate way is used.
if [ "$CONTAINER_TIMEZONE" ]; then
cp /usr/share/zoneinfo/${CONTAINER_TIMEZONE} /etc/localtime && \
echo "${CONTAINER_TIMEZONE}" >  /etc/timezone && \
echo "Container timezone set to: $CONTAINER_TIMEZONE"
fi

echo $CONTAINER_TIMEZONE

cd $WORKING_PATH
composer install

# adding to www-data
mkdir -p $WORKING_PATH/storage/framework/cache
mkdir -p $WORKING_PATH/storage/framework/sessions
mkdir -p $WORKING_PATH/storage/framework/views

# add this one if you are using laravel excel 3.1
# mkdir -p $WORKING_PATH/storage/framework/cache/laravel-excel

service php8.2-fpm start

cd $WORKING_PATH
php artisan clear-compiled
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan queue:restart &

/bin/bash -c  "npm install"

chown -R www-data:www-data $WORKING_PATH

echo "Server is Ready!"

nginx -g "daemon off;"
