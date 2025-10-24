#!/usr/bin/env bash
set -e

echo ">> Post-create: installing PHP and Node dependencies"
if [ -f composer.json ]; then
  composer install --no-interaction --prefer-dist
fi

if [ -f package.json ]; then
  npm install
fi

# .env
if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    cp .env.example .env
  else
    touch .env
  fi
fi

# Laravel key & storage link
php artisan key:generate || true
php artisan storage:link || true

# Run migrations (db container may still be booting; retry)
for i in {1..20}; do
  if mysql -h db -u bscn -pbscn -e "SELECT 1" >/dev/null 2>&1; then
    php artisan migrate --seed || true
    break
  else
    echo "Waiting for db... ($i/20)"
    sleep 3
  fi
done
