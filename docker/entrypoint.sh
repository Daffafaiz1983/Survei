#!/bin/sh

# Wait for database to be ready
echo "Waiting for database connection..."
while ! nc -z db 3306; do
  sleep 1
done
echo "Database is ready!"

# Wait for Redis to be ready
echo "Waiting for Redis connection..."
while ! nc -z redis 6379; do
  sleep 1
done
echo "Redis is ready!"

# Generate application key if not exists
if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
fi

# Generate application key
php artisan key:generate --force

# Clear and cache configuration
php artisan config:clear
php artisan config:cache

# Clear and cache routes
php artisan route:clear
php artisan route:cache

# Clear and cache views
php artisan view:clear
php artisan view:cache

# Run database migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Set proper permissions
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html/storage
chmod -R 755 /var/www/html/bootstrap/cache

echo "Application setup completed!"

# Execute the main command
exec "$@"
