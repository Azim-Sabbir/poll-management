#!/bin/bash

# Run migrations
php artisan migrate --force

# Run composer dump-autoload
composer dump-autoload

# Start Laravel Reverb in the background
php artisan reverb:start --host=0.0.0.0 --port=8080 &

# Start Laravel Serve in the foreground
php artisan serve --host=0.0.0.0 --port=80
