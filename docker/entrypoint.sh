#!/bin/sh

# Exit on error
set -e

# Replace port in nginx config if PORT env var is set (Railway/Render)
if [ -n "$PORT" ]; then
    echo "Replacing port 80 with $PORT in nginx config..."
    sed -i "s/listen 80;/listen $PORT;/g" /etc/nginx/http.d/default.conf
    sed -i "s/listen \[::\]:80;/listen [::]:$PORT;/g" /etc/nginx/http.d/default.conf
fi

# Cache Laravel components for production
echo "Caching config and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Run migrations if database is ready
echo "Running migrations..."
php artisan migrate --force

# Create symbolic link for storage
php artisan storage:link || true

# Start Supervisor (which will start PHP-FPM and Nginx)
echo "Starting Supervisor..."
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
