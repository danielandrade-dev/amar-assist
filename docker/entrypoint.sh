#!/bin/bash

# Aguardar o MySQL iniciar
echo "Aguardando o MySQL..."
sleep 10

# Criar diretórios necessários
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/framework/cache
mkdir -p /var/www/bootstrap/cache

# Configurar permissões
chmod -R 775 /var/www/storage
chmod -R 775 /var/www/bootstrap/cache
chown -R www-data:www-data /var/www

# Limpar e otimizar o Laravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan optimize:clear

# Iniciar o PHP-FPM
php-fpm 