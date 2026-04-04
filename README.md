## GestionTienda

Aplicacion web desarrollada con Laravel 12 que permite gestionar el catálogo de productos de una tienda, con sistema de autenticacion y control de acceso basado en roles (Admin y Empleado).

## Tecnologias

- PHP 8.2+
- Laravel 12
- MySQL (con HeidiSQL)
- Laravel Breeze — autenticacion
- spatie/laravel-permission — roles y permisos
- Blade — motor de plantillas
- Tailwind CSS — estilos
- Node.js / npm — compilacion de assets

## Requisitos previos

- PHP 8.2 o superior
- Composer
- Node.js y npm
- MySQL o MariaDB
- Git

## Instalacion y ejecucion

1. Clonar el repositorio
```bash
git clone https://github.com/tu-usuario/gestion-tienda.git
cd gestion-tienda
```
2. Instalar dependencias de PHP
```bash
composer install
```
3. Instalar dependencias de Node
```bash
npm install
```
4. Configurar el entorno
```bash
cp .env.example .env
php artisan key:generate
```
Edita el archivo .env con tus datos de base de datos:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_tienda
DB_USERNAME=root
DB_PASSWORD=
```
5. Crear la base de datos

Crea una base de datos llamada gestion_tienda en MySQL.

6. Ejecutar migraciones y seeds
```bash
php artisan migrate --seed
```
7. Compilar assets
```bash
npm run dev
```
8. Iniciar el servidor
```bash
php artisan serve
Accede en: http://localhost:8000
```

## Credenciales de prueba

| Rol              | Email                         | Contraseña   |
|------------------|-------------------------------|--------------|
| Administrador    | admin@gestiontienda.com       |  password    |
| Empleado         | empleado@gestiontienda.com    |  password    |

##  Estructura del proyecto
```bash
gestion-tienda/
├── app/Http/Controllers/   # Controladores
├── app/Models/             # Modelos Eloquent
├── app/Services/           # Lógica de negocio
├── database/migrations/    # Migraciones de BD
├── database/seeders/       # Datos de prueba
├── resources/views/        # Vistas Blade
├── routes/web.php          # Rutas
├── tests/                  # Pruebas automatizadas
└── docs/                   # Documentación completa
```
## Documentacion

Toda la documentación del proyecto se encuentra en la carpeta docs/:
- [docs/requerimientos/](docs/Requerimientos/). — Análisis de requerimientos funcionales y no funcionales
- [docs/diagramas/](docs/Diagramas/). — Diagramas UML y modelo entidad-relación
- [docs/mockups/](docs/Mockups/). — Wireframes de las interfaces
- [docs/manuales/](docs/Manuales/). — Manual de usuario y manual técnico

## Ejecutar pruebas
```bash
php artisan test
```

## Flujo de ramas
- main — versión estable y lista para producción
- develop — rama de desarrollo activo
