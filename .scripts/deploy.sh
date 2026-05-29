#!/bin/bash

# entra em modo de manutenção
(php artisan down) || true

# pegar as mudanças
git pull origin main

# instalar composer
composer install --no-dev --o-interaction --prefer-dist --optimize-autoloader

# optimize
php artisan optimize

# compilar os assets
npm run build

# rodar as migrations
php artisan migrate --force

# sair do modo de manutenção
php artisan up
