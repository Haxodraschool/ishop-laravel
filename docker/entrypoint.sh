#!/bin/sh

# Exit on error
set -e

# Cache Laravel components for production
echo "Caching config and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Run migrations if database is ready (optional - better done in Render pre-deploy)
# php artisan migrate --force

# Create symbolic link for storage
php artisan storage:link || true

# Start Supervisor (which will start PHP-FPM and Nginx)
echo "Starting Supervisor..."
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
