# Solución de Error 500 en Producción

## Problema

Error: `Route [workshop.settings] not defined`

## Causa

Los archivos de caché compilados en el servidor contienen referencias a rutas antiguas que ya no existen.

## Solución

### Paso 1: Conectarse al Servidor

```bash
ssh usuario@ges.merke.net
```

### Paso 2: Navegar al Directorio del Proyecto

```bash
cd /var/www/ges
```

### Paso 3: Limpiar Todas las Cachés

```bash
# Limpiar caché de configuración
php artisan config:clear

# Limpiar caché de rutas
php artisan route:clear

# Limpiar caché de vistas
php artisan view:clear

# Limpiar caché general
php artisan cache:clear

# Limpiar archivos compilados de Blade
rm -rf storage/framework/views/*
```

### Paso 4: Actualizar Código desde GitHub

```bash
# Asegurarse de tener los últimos cambios
git pull origin main
```

### Paso 5: Instalar/Actualizar Dependencias

```bash
# Composer
composer install --optimize-autoloader --no-dev

# NPM
npm install
npm run build
```

### Paso 6: Recompilar Cachés (Producción)

```bash
# Solo en producción, recompilar cachés para mejor rendimiento
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Paso 7: Reiniciar Servicios

```bash
# Reiniciar PHP-FPM
sudo systemctl restart php8.3-fpm

# Reiniciar Nginx
sudo systemctl restart nginx

# Si usas Supervisor para colas
sudo supervisorctl restart all
```

### Paso 8: Verificar

Abre el navegador y verifica que el sitio funcione correctamente.

## Comando Rápido (Todo en Uno)

```bash
cd /var/www/ges && \
php artisan config:clear && \
php artisan route:clear && \
php artisan view:clear && \
php artisan cache:clear && \
rm -rf storage/framework/views/* && \
git pull origin main && \
composer install --optimize-autoloader --no-dev && \
npm install && \
npm run build && \
php artisan config:cache && \
php artisan route:cache && \
php artisan view:cache && \
sudo systemctl restart php8.3-fpm && \
sudo systemctl restart nginx
```

## Prevención Futura

### Usar el Script de Deploy

Si creaste el script `deploy.sh`, úsalo para futuras actualizaciones:

```bash
cd /var/www/ges
./deploy.sh
```

Este script ya incluye todos los pasos de limpieza de caché.

## Verificar Logs

Si persisten los errores:

```bash
# Ver últimos errores de Laravel
tail -f storage/logs/laravel.log

# Ver errores de Nginx
sudo tail -f /var/log/nginx/ges-error.log
```

## Notas Importantes

⚠️ **IMPORTANTE**: En producción, siempre limpia las cachés antes de recompilarlas
⚠️ **IMPORTANTE**: Después de hacer `git pull`, siempre ejecuta `composer install` y `npm run build`
⚠️ **IMPORTANTE**: Reinicia PHP-FPM después de cambios importantes

## Errores Comunes

### "Permission denied" al limpiar caché

```bash
sudo chown -R www-data:www-data storage/
sudo chmod -R 775 storage/
```

### Cambios no se reflejan

```bash
# Forzar limpieza completa
php artisan optimize:clear
```

### Error de Livewire

```bash
# Publicar assets de Livewire
php artisan livewire:publish --assets --force
```
