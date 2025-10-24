@echo off
cd /d C:\laragon\www\bscn\app\Http
rename kernel.php Kernel.php

cd /d C:\laragon\www\bscn
composer dump-autoload -o
php artisan optimize:clear
php artisan config:clear
php artisan route:clear

pause