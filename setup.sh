#!/bin/bash
set -e

echo "Installing dependencies..."
docker run --rm -v $(pwd)/src:/app composer install --no-interaction --prefer-dist

echo "Generating app key..."
docker run --rm -v $(pwd)/src:/app php:8.3-cli-alpine php artisan key:generate

echo "Copying .env..."
cp src/.env.example src/.env

echo "Running migrations..."
docker run --rm -v $(pwd)/src:/app php:8.3-cli-alpine php artisan migrate

echo "Done. Run: docker compose up -d"