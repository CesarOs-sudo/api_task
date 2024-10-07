<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>


## API en Laravel

Este es un proyecto de un crud basico de tareas con atenticacion JWT para usuarios solicitado por DQ

## Instalacion del proyecto
- Clonar el proyecto en la carpeta principal de tu servidor local
- Crear una base de datos en tu local llamada api_task
- Entrar a la carpeta del proyecto clonado
- Una vez clonado el proyecto se tiene que renombrar el archivo .env.example por .env
- Entrar al archivo .env y llenar los datos de la conexión a base de datos como usuario, password, host y colocar el nombre de la base de datos api_task
- Instalar las dependencias con el comando "composer install"
- Ejecutar las migraciones con el comando "php artisan migrate"
- Ejecutar los seeders con el comando "php artisan db:seed"
- Ejecutar "php artisan jwt:secret" para obtener la clave secreta
- Asta este punto el API ya se encontrara con datos y lista para usarse


## Ejecutar en local
El API se puede probar con ThunderClient o Postman 
- Ejecutamos el comando "php artisan serve" para que nuestra aplicacion inicie, el resultado de ese comando nos dara nuestro localhos y el puerto donde se ejecuta el API
- Crear un nuevo request en thunder client de tipo post con la ruta deseada ya sea para hacer login o logout
- Ruta para autenticar el usuario "/api/auth/login"
- Ruta para terminar la autenticacion "/api/auth/logout"
- 
Las rutas para hacer crud en Tareas son 
- /api/task/gelAll  
- /api/task/create
- /api/task/update/param
- /api/task/delete/param



