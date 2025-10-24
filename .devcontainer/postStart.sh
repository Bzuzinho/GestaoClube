#!/usr/bin/env bash
set -e

# Arrancar Laravel
if ! pgrep -f "php artisan serve --host=0.0.0.0 --port=8000" >/dev/null; then
  nohup php artisan serve --host=0.0.0.0 --port=8000 >/tmp/artisan.log 2>&1 &
fi

# Arrancar Vite (se existir)
if [ -f package.json ] && grep -q '"dev"' package.json; then
  if ! pgrep -f "vite" >/dev/null; then
    nohup npm run dev >/tmp/vite.log 2>&1 &
  fi
fi
