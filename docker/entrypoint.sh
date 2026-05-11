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
echo "Caching config and routes..."
# We use || true here to prevent the container from crashing if these fail (e.g. during build or missing DB)
php artisan config:cache || echo "Config cache failed, skipping..."
php artisan route:cache || echo "Route cache failed, skipping..."
php artisan view:cache || echo "View cache failed, skipping..."
php artisan event:cache || echo "Event cache failed, skipping..."

# Run migrations if database is ready
echo "Running migrations..."
php artisan migrate --force || echo "Migration failed! Please check your database connection."

# Create symbolic link for storage
echo "Creating storage link..."
php artisan storage:link || true

# Start Supervisor (which will start PHP-FPM and Nginx)
echo "Starting Supervisor..."
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
