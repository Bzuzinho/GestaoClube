#!/usr/bin/env bash
set -e

echo ">> Installing PHP/Node deps"

# Garante pdo_mysql (algumas imagens já têm)
php -m | grep -qi pdo_mysql || (sudo apt-get update && sudo apt-get install -y php8.3-mysql || true)

[ -f composer.json ] && composer install --no-interaction --prefer-dist || true
[ -f package.json ] && npm install || true

# .env
if [ ! -f .env ]; then
  if [ -f .env.example ]; then
    cp .env.example .env
  else
    cat > .env <<'EOF'
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bscn
DB_USERNAME=bscn
DB_PASSWORD=bscn
EOF
  fi
fi

php artisan key:generate || true
php artisan storage:link || true

# Espera pelo MySQL e migra
for i in {1..40}; do
  if mysql -h 127.0.0.1 -u bscn -pbscn -e "SELECT 1" >/dev/null 2>&1; then
    php artisan migrate --seed || true
    break
  else
    echo "Waiting for mysql... ($i/40)"
    sleep 3
  fi
done
