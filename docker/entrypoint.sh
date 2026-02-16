#!/bin/sh

set -e

echo "Starting ChatHub deployment..."

# Configure Nginx to use Heroku's PORT
echo "Configuring Nginx for port $PORT..."
sed -i "s/listen 8080;/listen ${PORT:-8080};/" /etc/nginx/http.d/default.conf

# Wait for database to be ready
echo "Waiting for database..."
sleep 5

# Run migrations
echo "Running migrations..."
php artisan migrate --force --no-interaction

# Clear and cache config
echo "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan filament:cache-components

# Create storage link if it doesn't exist
if [ ! -L /var/www/html/public/storage ]; then
    php artisan storage:link
fi

echo "ChatHub is ready!"

# Execute the main command
exec "$@"
