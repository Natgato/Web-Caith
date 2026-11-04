# Web CAITH

Plataforma institucional multipágina de Casa CAITH, programa del Centro Yanapanakusun, Cusco. Laravel 12, PHP 8.2+, Blade, Eloquent, MySQL y JavaScript/CSS local. Se conserva la identidad vino y la portada ilustrada.

## Acceso local

- Web: http://localhost/Web-Caith/public/
- Administración: http://localhost/Web-Caith/public/admin/login
- Proyecto: C:/xampp/htdocs/Web-Caith
- Base independiente: web_caith. No se modificó tienda.
- Apache y MySQL deben estar encendidos en XAMPP.
- Rama: codex/caith-plataforma-institucional. Sin commits ni push automáticos.

## Instalación

1. Instalar PHP 8.2+, Composer y un motor MySQL compatible. Crear una base vacía web_caith.
2. Ejecutar `composer install`.
3. Copiar `.env.example` a `.env` y configurar los datos propios de conexión. No compartir .env.
4. Ejecutar `php artisan key:generate` solo en una instalación nueva. No rotar la clave de una instalación con datos cifrados sin plan de migración.
5. Ejecutar `php artisan migrate --seed`. No usar migrate:fresh sobre datos existentes.
6. Crear una cuenta con `php artisan caith:admin`; la contraseña se solicita oculta y se almacena con hash. No existe registro público de administradores.
7. En desarrollo local, habilitar `CAITH_FORMS_ENABLED=true` si se van a probar solicitudes ficticias. El ejemplo mantiene los formularios deshabilitados hasta apertura deliberada.
8. Abrir la web con Apache apuntando a public, o ejecutar `php artisan serve`.

La interfaz entregada no requiere npm ni Vite activos: las fuentes, CSS, JavaScript e ilustraciones se sirven localmente. package.json conserva las herramientas Vite existentes.

## Funcionalidades

Inicio como índice visual; páginas institucionales independientes; cronología interactiva; noticias con categorías y detalle; testimonios con revisión, consentimiento vigente y nombre público; galería de fotografías y videos; estadísticas con fuentes, gráficos y tabla; donaciones solo como próximamente; formularios de voluntariado, actividades, patrocinio y contacto; solicitudes de privacidad; panel con CRUD, filtros, estados y bandejas.

Rutas públicas principales: /nosotros, /nosotros/mision-vision, /nosotros/historia, /nosotros/linea-de-tiempo, /nuestra-labor, /realidad-social, /estadisticas, /historias, /noticias, /galeria, /como-ayudar, /participa/voluntariado, /participa/actividades, /participa/patrocinio, /contacto, /privacidad y /donaciones. `php artisan route:list --except-vendor` muestra las 52 rutas registradas. Nuevas páginas editoriales pueden accederse mediante /paginas/{slug}.

Administración: /admin/login y /admin. Contenidos en /admin/contenidos/{modulo}; bandejas en /admin/bandeja/participacion y /admin/bandeja/contacto. Los consentimientos se gestionan dentro de cada testimonio, en un formulario separado.

## Modelo de datos y arquitectura

Presentación en resources/views; procesamiento en app/Http/Controllers, app/Http/Requests y app/Services; persistencia en app/Models y database/migrations. Gate manage-site protege todas las rutas administrativas.

Tablas propias: pages, posts, testimonials, consents, participation_requests, contact_messages, media, timeline_events y statistics. Users incorpora is_admin, excluido de asignación pública. Hay claves foráneas, índices, estados y marcas de revisión.

Los campos personales de solicitudes, el alcance de consentimientos y referencias de autorización multimedia se cifran con la clave de Laravel. Los documentos no se sirven desde carpetas públicas: se guardan en storage/app/private y se entregan por rutas con control de acceso. No ejecutar storage:link para estos archivos. La regla raíz .htaccess bloquea accesos directos al proyecto fuera de public bajo XAMPP.

## Uso del panel

- Crear borradores; completar fuente y datos; confirmar revisión antes de publicar.
- Para testimonios: guardar borrador, registrar evidencia PDF privada y alcance del consentimiento, revisar el nombre público, y publicar en un paso separado.
- Para menores: verificar representación y autorizaciones aplicables. La marca en el sistema no sustituye la revisión institucional/jurídica.
- Retirar consentimiento oculta el relato y bloquea su fotografía. Una fotografía de testimonio nunca se convierte automáticamente en archivo público de galería.
- Revisar participación/contacto mediante sus estados. No se envían correos reales.
- Para solicitudes legítimas de eliminación, revisar también testimonios, consentimientos y multimedia relacionados; no hay borrado automático por solicitudes anónimas no verificadas.

## Seeders y contenido

`php artisan db:seed` carga 13 páginas institucionales y 3 hitos en borrador provisional, además de 2 estadísticas nacionales históricas verificadas. Los borradores institucionales solo se previsualizan si APP_ENV=local y CAITH_PREVIEW_INSTITUTIONAL=true. No aplica a testimonios ni material personal.

`php artisan db:seed --class=DemoSeeder` es opcional y crea un artículo ficticio en borrador, solo en local/testing. No crea credenciales ni testimonios reales.

Las noticias, testimonios y fotografías se mantienen vacíos hasta su revisión. Las cifras cargadas son nacionales de 2019 y 2022, no actuales de Cusco ni resultados de CAITH. Fuente y limitaciones: docs/VALIDACION-Y-FUENTES.md.

## Pruebas

`php artisan test` ejecuta 25 pruebas y 213 aserciones, con SQLite en memoria y datos ficticios: rutas, permisos, login/logout, límite de intentos, CRUD, validación, cifrado, publicación, consentimientos, retiro, archivos y filtros. APP_URL del test es independiente del subdirectorio XAMPP.

También se verificaron en navegador Chrome: escritorio y móvil, navegación, cronología sin JavaScript, movimiento reducido, formularios reales contra MySQL, login/logout, editor, carga privada, bloqueo CSRF sin token y acceso Apache prohibido a .env/.git/archivos privados. Las cuentas y solicitudes de esa comprobación fueron temporales y se eliminaron. Evidencia local ignorada por Git: .impeccable/review/platform.

## Pendientes antes de producción

Validar misión, visión, textos, canales de contacto, fotografías y consentimientos reales; definir responsable, plazos y procedimiento de privacidad conforme a la normativa peruana; crear cuentas individuales; HTTPS y APP_DEBUG=false; credenciales y permisos propios de base; copias de seguridad y restauración; configurar correo solo con autorización. Los formularios permanecen deshabilitados fuera de local salvo habilitación explícita.

No se implementan pagos, cuentas bancarias, comentarios públicos ni integración con el sistema interno de beneficiarias. El código propio aún requiere una decisión de licencia del equipo/institución.

## Recursos y documentación

- docs/IMPLEMENTACION.md: diagnóstico y arquitectura.
- docs/VALIDACION-Y-FUENTES.md: fuentes, estados y límites.
- DESIGN.md y .impeccable/design.json: sistema visual.
- .impeccable/asset-provenance.md y community-asset-provenance.md: ilustraciones originales y prompts.
- public/fonts: fuentes Barlow y Cormorant Garamond con licencias OFL.
- Laravel: https://laravel.com/docs/12.x

No se incluyen credenciales reales en este documento ni en seeders. Las contraseñas se definen localmente.
