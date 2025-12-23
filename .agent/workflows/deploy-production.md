---
description: Guía completa para desplegar el proyecto desde GitHub usando Deploy Keys y Nginx
---

# Guía de Despliegue a Producción

Esta guía te ayudará a desplegar tu aplicación Laravel desde GitHub a un servidor de producción usando Deploy Keys y Nginx.

## Requisitos Previos

-   Servidor Linux (Ubuntu 20.04+ o similar)
-   Acceso root o sudo
-   Dominio apuntando a tu servidor (opcional pero recomendado)

## Paso 1: Preparar el Servidor

### 1.1 Actualizar el Sistema

```bash
sudo apt update
sudo apt upgrade -y
```

### 1.2 Instalar Dependencias Necesarias

```bash
# PHP 8.3 y extensiones
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install -y php8.3 php8.3-fpm php8.3-cli php8.3-common php8.3-mysql \
    php8.3-zip php8.3-gd php8.3-mbstring php8.3-curl php8.3-xml php8.3-bcmath \
    php8.3-intl php8.3-redis

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Nginx
sudo apt install -y nginx

# MySQL (si no lo tienes)
sudo apt install -y mysql-server

# Node.js y npm (para compilar assets)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Git
sudo apt install -y git
```

## Paso 2: Configurar Deploy Key en GitHub

### 2.1 Generar SSH Key en el Servidor

```bash
# Crear directorio para el proyecto
sudo mkdir -p /var/www/ges
sudo chown $USER:$USER /var/www/ges
cd /var/www/ges

# Generar SSH key (sin passphrase para automatización)
ssh-keygen -t ed25519 -C "deploy@tu-servidor.com" -f ~/.ssh/ges_deploy_key -N ""
```

### 2.2 Agregar Deploy Key a GitHub

```bash
# Mostrar la clave pública
cat ~/.ssh/ges_deploy_key.pub
```

**Pasos en GitHub:**

1. Ve a tu repositorio en GitHub
2. Click en **Settings** → **Deploy keys**
3. Click en **Add deploy key**
4. Título: `Servidor Producción`
5. Pega el contenido de `ges_deploy_key.pub`
6. ✅ **NO** marques "Allow write access" (solo lectura)
7. Click en **Add key**

### 2.3 Configurar SSH para usar la Deploy Key

```bash
# Crear/editar config de SSH
nano ~/.ssh/config
```

Agregar:

```
Host github.com-ges
    HostName github.com
    User git
    IdentityFile ~/.ssh/ges_deploy_key
    IdentitiesOnly yes
```

```bash
# Dar permisos correctos
chmod 600 ~/.ssh/config
chmod 600 ~/.ssh/ges_deploy_key
```

### 2.4 Probar Conexión

```bash
ssh -T git@github.com-ges
# Deberías ver: "Hi usuario/repo! You've successfully authenticated..."
```

## Paso 3: Clonar el Repositorio

```bash
cd /var/www/ges

# Clonar usando el alias configurado
git clone git@github.com-ges:TU_USUARIO/TU_REPOSITORIO.git .

# Verificar
ls -la
```

## Paso 4: Configurar la Aplicación

### 4.1 Instalar Dependencias

```bash
# Dependencias de Composer (producción)
composer install --optimize-autoloader --no-dev

# Dependencias de Node
npm install
```

### 4.2 Configurar Variables de Entorno

```bash
# Copiar archivo de ejemplo
cp .env.example .env

# Editar configuración
nano .env
```

Configurar:

```env
APP_NAME="GES - Sistema de Gestión de Taller"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://tu-dominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ges_production
DB_USERNAME=ges_user
DB_PASSWORD=TU_PASSWORD_SEGURO

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username
MAIL_PASSWORD=tu_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tu-dominio.com
MAIL_FROM_NAME="${APP_NAME}"

QUEUE_CONNECTION=database
```

### 4.3 Generar Application Key

```bash
php artisan key:generate
```

### 4.4 Crear Base de Datos

```bash
# Entrar a MySQL
sudo mysql

# Ejecutar en MySQL:
CREATE DATABASE ges_production CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'ges_user'@'localhost' IDENTIFIED BY 'TU_PASSWORD_SEGURO';
GRANT ALL PRIVILEGES ON ges_production.* TO 'ges_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 4.5 Ejecutar Migraciones

```bash
php artisan migrate --force
php artisan db:seed --force
```

### 4.6 Compilar Assets

```bash
npm run build
```

### 4.7 Optimizar para Producción

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 4.8 Configurar Permisos

```bash
# Dar permisos correctos
sudo chown -R www-data:www-data /var/www/ges
sudo chmod -R 755 /var/www/ges
sudo chmod -R 775 /var/www/ges/storage
sudo chmod -R 775 /var/www/ges/bootstrap/cache
```

## Paso 5: Configurar Nginx

### 5.1 Crear Configuración de Nginx

```bash
sudo nano /etc/nginx/sites-available/ges
```

Contenido:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name tu-dominio.com www.tu-dominio.com;

    root /var/www/ges/public;
    index index.php index.html;

    # Logs
    access_log /var/log/nginx/ges-access.log;
    error_log /var/log/nginx/ges-error.log;

    # Aumentar tamaño máximo de subida
    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache para assets estáticos
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

### 5.2 Habilitar el Sitio

```bash
# Crear enlace simbólico
sudo ln -s /etc/nginx/sites-available/ges /etc/nginx/sites-enabled/

# Eliminar sitio por defecto (opcional)
sudo rm /etc/nginx/sites-enabled/default

# Probar configuración
sudo nginx -t

# Reiniciar Nginx
sudo systemctl restart nginx
```

## Paso 6: Configurar SSL con Let's Encrypt (Recomendado)

```bash
# Instalar Certbot
sudo apt install -y certbot python3-certbot-nginx

# Obtener certificado SSL
sudo certbot --nginx -d tu-dominio.com -d www.tu-dominio.com

# Renovación automática (ya está configurada por defecto)
sudo certbot renew --dry-run
```

## Paso 7: Configurar Supervisor para Colas (Opcional)

Si usas colas de Laravel:

```bash
# Instalar Supervisor
sudo apt install -y supervisor

# Crear configuración
sudo nano /etc/supervisor/conf.d/ges-worker.conf
```

Contenido:

```ini
[program:ges-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/ges/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/ges/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Recargar Supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start ges-worker:*
```

## Paso 8: Configurar Cron para Tareas Programadas

```bash
# Editar crontab
sudo crontab -e -u www-data
```

Agregar:

```
* * * * * cd /var/www/ges && php artisan schedule:run >> /dev/null 2>&1
```

## Paso 9: Script de Actualización

Crear un script para futuras actualizaciones:

```bash
nano /var/www/ges/deploy.sh
```

Contenido:

```bash
#!/bin/bash

echo "🚀 Iniciando despliegue..."

# Modo mantenimiento
php artisan down

# Obtener últimos cambios
git pull origin main

# Instalar/actualizar dependencias
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Ejecutar migraciones
php artisan migrate --force

# Limpiar y optimizar
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Reiniciar servicios
sudo supervisorctl restart ges-worker:*

# Salir de modo mantenimiento
php artisan up

echo "✅ Despliegue completado!"
```

```bash
# Dar permisos de ejecución
chmod +x /var/www/ges/deploy.sh
```

## Paso 10: Verificación Final

### 10.1 Verificar Servicios

```bash
# Estado de Nginx
sudo systemctl status nginx

# Estado de PHP-FPM
sudo systemctl status php8.3-fpm

# Estado de MySQL
sudo systemctl status mysql

# Logs de errores
sudo tail -f /var/log/nginx/ges-error.log
sudo tail -f /var/www/ges/storage/logs/laravel.log
```

### 10.2 Probar la Aplicación

1. Abre tu navegador
2. Ve a `https://tu-dominio.com`
3. Verifica que cargue correctamente
4. Prueba el login
5. Verifica todas las funcionalidades principales

## Solución de Problemas Comunes

### Error 500 - Internal Server Error

```bash
# Verificar permisos
sudo chown -R www-data:www-data /var/www/ges/storage
sudo chmod -R 775 /var/www/ges/storage

# Ver logs
tail -f /var/www/ges/storage/logs/laravel.log
```

### Error de Conexión a Base de Datos

```bash
# Verificar credenciales en .env
nano /var/www/ges/.env

# Probar conexión
php artisan tinker
>>> DB::connection()->getPdo();
```

### Assets no cargan (404)

```bash
# Recompilar assets
cd /var/www/ges
npm run build

# Limpiar cache
php artisan cache:clear
```

### Permisos de Git

```bash
# Si tienes problemas al hacer git pull
sudo chown -R $USER:$USER /var/www/ges/.git
```

## Mantenimiento Regular

### Actualizar la Aplicación

```bash
cd /var/www/ges
./deploy.sh
```

### Backup de Base de Datos

```bash
# Crear backup
mysqldump -u ges_user -p ges_production > backup_$(date +%Y%m%d).sql

# Restaurar backup
mysql -u ges_user -p ges_production < backup_20231223.sql
```

### Monitoreo de Logs

```bash
# Logs de Laravel
tail -f /var/www/ges/storage/logs/laravel.log

# Logs de Nginx
sudo tail -f /var/log/nginx/ges-error.log
sudo tail -f /var/log/nginx/ges-access.log
```

## Seguridad Adicional

### Configurar Firewall

```bash
# Habilitar UFW
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
sudo ufw status
```

### Deshabilitar Funciones Peligrosas de PHP

```bash
sudo nano /etc/php/8.3/fpm/php.ini
```

Buscar y configurar:

```ini
disable_functions = exec,passthru,shell_exec,system,proc_open,popen
```

```bash
sudo systemctl restart php8.3-fpm
```

## Notas Importantes

-   ⚠️ **NUNCA** subas el archivo `.env` a GitHub
-   ⚠️ Mantén las claves SSH privadas seguras
-   ⚠️ Usa contraseñas fuertes para la base de datos
-   ⚠️ Habilita siempre SSL en producción
-   ⚠️ Realiza backups regulares de la base de datos
-   ⚠️ Monitorea los logs regularmente

## Recursos Adicionales

-   [Documentación de Laravel](https://laravel.com/docs)
-   [Documentación de Nginx](https://nginx.org/en/docs/)
-   [Let's Encrypt](https://letsencrypt.org/)
-   [Supervisor](http://supervisord.org/)
