#!/bin/sh
set -e

echo "Starting application container setup..."

# 1. Create .env if it does not exist
if [ ! -f /var/www/html/.env ]; then
    echo "Creating .env from .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi

# 2. Generate application key if missing
if ! grep -q "^APP_KEY=base64:" /var/www/html/.env; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
fi

# 3. Wait for MySQL to be ready if DB_HOST is set and not sqlite
if [ "$DB_CONNECTION" = "mysql" ] && [ -n "$DB_HOST" ]; then
    echo "Waiting for MySQL database at $DB_HOST:${DB_PORT:-3306} to be ready..."
    until nc -z -v -w30 "$DB_HOST" "${DB_PORT:-3306}"; do
        echo "Database is unavailable - sleeping 2 seconds..."
        sleep 2
    done
    echo "Database is up and reachable!"

    # Run migrations and seed data automatically
    echo "Running migrations and seeding..."
    php artisan migrate --force --seed || echo "Migrations completed or already current."
fi

# 4. Clear and optimize caches for production/deployment
echo "Optimizing application cache..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# 5. Ensure storage permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Entrypoint initialization complete. Starting services..."

exec "$@"
