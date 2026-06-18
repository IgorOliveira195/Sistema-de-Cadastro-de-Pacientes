#!/bin/sh
set -e

echo "Aguardando MySQL..."
until php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT:-3306}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
  sleep 2
done

if [ ! -f vendor/autoload.php ]; then
  echo "Instalando dependências PHP..."
  composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "" ]; then
  php artisan key:generate --force --no-interaction
fi

chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

php artisan migrate --force --no-interaction

USER_COUNT=$(php -r "
require 'vendor/autoload.php';
\$app = require 'bootstrap/app.php';
\$kernel = \$app->make(Illuminate\Contracts\Console\Kernel::class);
\$kernel->bootstrap();
echo App\Models\User::count();
")

if [ "$USER_COUNT" = "0" ]; then
  php artisan db:seed --force --no-interaction
fi

exec "$@"
