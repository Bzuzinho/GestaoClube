#!/usr/bin/env bash
set -e

# Start Laravel + Vite in background tmux/session if available, otherwise nohup
if ! pgrep -f "php artisan serve" >/dev/null; then
  nohup php artisan serve --host=0.0.0.0 --port=8000 >/tmp/artisan.log 2>&1 &
fi

# Start Vite only if package.json has scripts.vite or dev
if [ -f package.json ] && grep -q '"dev"' package.json; then
  if ! pgrep -f "vite" >/dev/null; then
    nohup npm run dev >/tmp/vite.log 2>&1 &
  fi
fi
