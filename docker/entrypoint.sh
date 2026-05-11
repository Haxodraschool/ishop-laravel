#!/bin/sh

# Exit on error
set -e

# Replace port in nginx config if PORT env var is set (Railway/Render)
if [ -n "$PORT" ]; then
    echo "Configuring Nginx to listen on port $PORT..."
    # More robust replacement for both IPv4 and IPv6
    sed -i "s/listen 80;/listen $PORT;/g" /etc/nginx/http.d/default.conf
    sed -i "s/listen \[::\]:80;/listen [::]:$PORT;/g" /etc/nginx/http.d/default.conf
else
    echo "PORT variable not set, using default port 80"
fi

# Cache Laravel components for production
echo "Caching config..."
php artisan config:cache || echo "Config cache failed"

# Only cache routes if we are sure there are no issues, 
# otherwise fresh routes are safer for health checks.
echo "Caching routes..."
php artisan route:cache || echo "Route cache failed, continuing with dynamic routes..."

# Run migrations with a timeout to prevent blocking health check indefinitely
echo "Running migrations..."
# Use a background process or ensure it doesn't block forever
php artisan migrate --force --no-interaction || echo "Migration failed, check DB connection"

# Create symbolic link for storage
echo "Creating storage link..."
php artisan storage:link --force || true

# Initialize volume if empty
if [ -d "/var/www/html/storage_initial" ] && [ -z "$(ls -A /var/www/html/storage/app/public)" ]; then
    echo "Initializing storage volume with seed images..."
    cp -R /var/www/html/storage_initial/* /var/www/html/storage/app/public/
    chown -R www-data:www-data /var/www/html/storage/app/public
fi

# Start Supervisor (which will start PHP-FPM and Nginx)
echo "Starting Supervisor..."
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
