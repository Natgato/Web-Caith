# Web CAITH

Plataforma digital y sitio web principal de WEB-CAITH.

## Requisitos

- PHP 8.2+
- Composer
- Node.js y npm
- MySQL 8+
- Git

## Clonar el repositorio

```bash
git clone https://github.com/Natgato/Web-Caith.git
cd Web-Caith
```

## Instalar dependencias de PHP

```bash
composer install
```

## Configurar variables de entorno

Copia el archivo de ejemplo y configura tu base de datos MySQL:

```bash
cp .env.example .env
```

Edita el archivo `.env` y configura la conexión a MySQL:

```env
APP_NAME=WebCaith
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webcaith
DB_USERNAME=root
DB_PASSWORD=tu_password
```

Si aún no tienes la base de datos creada, créala con MySQL:

```bash
mysql -u root -p -e "CREATE DATABASE webcaith CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

## Generar la clave de la aplicación

```bash
php artisan key:generate
```

## Ejecutar migraciones

```bash
php artisan migrate
```

## Instalar dependencias del frontend

```bash
npm install
npm run build
```

## Iniciar el proyecto

```bash
php artisan serve
```

Abre tu navegador en:

```text
http://localhost:8000
```

## Nota importante

La vista principal se referencia en `routes/web.php` como `view('index')`, pero en el proyecto existe el archivo `resources/views/Index.blade.php` con mayúscula inicial. En sistemas Linux con archivos sensibles a mayúsculas/minúsculas, esto puede provocar que la página principal falle con un error de vista no encontrada.

Para evitar este problema, se recomienda:

- renombrar el archivo a `index.blade.php`, o
- cambiar la llamada en `routes/web.php` a `view('Index')`

## Estructura general

- `app/` — lógica de la aplicación
- `routes/` — rutas web y API
- `resources/views/` — vistas Blade
- `public/` — archivos públicos y compilados
- `database/` — migraciones y seeders
- `config/` — configuración del proyecto

## Desarrollo

Para trabajar en modo desarrollo con Vite y Laravel:

```bash
npm run dev
php artisan serve
```

## Licencia

Este proyecto se distribuye bajo la licencia definida en el repositorio.
