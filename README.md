# Instrucciones de despliegue (simplificadas)

## Requisitos mínimos
- PHP >= 8.x, Composer, MySQL (o DB compatible).
- En local con WAMP: Apache + PHP + MySQL.
- Git (opcional).

## Despliegue local (WAMP / Windows) — comandos mínimos
Abrir PowerShell o CMD en la raíz del proyecto:

1. Instalar dependencias:
   composer install

2. Copiar el entorno:
   copy .env.example .env

3. Generar clave:
   php artisan key:generate

4. Configurar la base de datos en `.env` (DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD).

5. Ejecutar migraciones:
   php artisan migrate --seed

6. Crear enlace a storage:
   php artisan storage:link

7. (Opcional) Generar docs Swagger:
   php artisan l5-swagger:generate

8. Servir en desarrollo (si no usa Apache de WAMP):
   php artisan serve --host=127.0.0.1 --port=8000

Comprobaciones rápidas:
- php artisan route:list
- Acceder a http://127.0.0.1:8000/api/nodes

## Despliegue en producción (Linux) — comandos mínimos
En el servidor, desde la carpeta del proyecto:

1. Actualizar código:
   git pull origin main

2. Instalar dependencias sin dev:
   composer install --no-dev --optimize-autoloader

3. Configurar `.env` y generar clave:
   cp .env.production .env
   php artisan key:generate --force

4. Ejecutar migraciones:
   php artisan migrate --force

5. Cachear config y rutas:
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache

6. Ajustar permisos (ejemplo nginx/php-fpm):
   chown -R www-data:www-data storage bootstrap/cache
   chmod -R 775 storage bootstrap/cache

7. Reiniciar servicios según stack:
   sudo systemctl restart php-fpm
   sudo systemctl reload nginx

Comprobaciones:
- php artisan route:list
- Revisar logs en storage/logs/laravel.log

## Pruebas opcionales con Postman

1. Archivo de colección:
   - Ruta en el proyecto: c:\wamp64\www\node-tree-api\node_tree_api.postmant_collection.json

2. Importar en Postman:
   - Abrir Postman → Import → File → seleccionar el JSON de la ruta anterior.

3. Crear entorno (Environment) en Postman:
   - Variable: base_url
   - Valor ejemplo: http://127.0.0.1:8000
   - (Opcional) Variable: X-Timezone con valor ejemplo: America/Argentina/Buenos_Aires

4. Usar la colección/importada:
   - GET nodos con profundidad:
     URL: {{base_url}}/api/nodes?depth=2
     Header opcional: X-Timezone: {{X-Timezone}}
   - DELETE nodo:
     URL: {{base_url}}/api/nodes/{{id}}
     Método: DELETE

5. Ejecutar collection runner (opcional):
   - Collection Runner → seleccionar la colección → seleccionar entorno → Run.
   - Para pruebas automatizadas, usar CSV/JSON con variables (por ejemplo `id`) en el runner.

Notas rápidas:
- Asegurarse de que la API esté corriendo (php artisan serve) y que `.env` tenga la configuración de DB correcta.
- Si cambia el host/puerto actualice la variable base_url en el entorno de Postman.

Fin — guía reducida para desplegar y verificar la aplicación rápidamente.
