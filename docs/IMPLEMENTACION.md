# Plataforma institucional CAITH

## Diagnóstico de partida

Laravel 12.70.2, PHP 8.2.12, Blade, JavaScript nativo y CSS local. Dependencias PHP instaladas y comprobadas. Existía una ruta `/`, una vista extensa de inicio, ilustraciones originales, menú móvil, historia seleccionable y FAQ. No había controladores de dominio, CRUD, formularios persistentes ni autenticación administrativa. Las migraciones disponibles eran las estándar de Laravel. MySQL estaba activo, pero `.env` apuntaba a `tienda`, ajena a esta ampliación. Se creó `web_caith` como esquema independiente; no se modifican tablas de `tienda`. La SQLite previa se conserva.

## Referencias revisadas

- Informe proporcionado «Avance del proyecto Web CAITH, Semanas 09 a 11», 17 páginas, especialmente pp. 3–12: entrevista, límite del sistema, accesibilidad y tres capas. El informe no aporta enlaces verificables para todas sus estadísticas. No se publican estimaciones derivadas de Cusco ni se convierte una opinión de entrevista en hecho general.
- Recopilación institucional del proyecto: origen de Casa CAITH en 1994, Yanapanakusun en 2001; síntesis para revisión local, no misión oficial autorizada.
- Documentación Laravel 12 de autenticación y validación: https://laravel.com/docs/12.x/authentication y https://laravel.com/docs/12.x/validation.
- Referencia normativa informativa: Ley 31047, https://busquedas.elperuano.pe/dispositivo/NL/1889434-1; reglamento, https://www.gob.pe/institucion/mtpe/normas-legales/1844983-009-2021-tr.
- Protección de datos: Reglamento aprobado por DS 016-2024-JUS, https://www.gob.pe/institucion/anpd/normas-legales/6554453-16-2024-jus. La implementación técnica no sustituye la validación institucional y jurídica antes de operar con datos reales.

## Arquitectura y datos

Presentación: layouts/componentes Blade públicos y administrativos. Aplicación: controladores, Form Requests, Gate de administración y servicios de publicación/consentimiento. Persistencia: modelos Eloquent, relaciones e índices; migraciones aditivas en una base nueva.

Tablas de dominio: pages, posts, testimonials, consents, participation_requests, contact_messages, media, timeline_events y statistics. Users recibe una marca administrativa no asignable desde formularios públicos. Publicación y autorización se comprueban en el servidor; nunca basta con esconder un botón. Datos privados y archivos se guardan fuera de public. Consentimientos y mensajes sensibles usan cifrado de Laravel.

La aplicación no se integra con seguimiento de beneficiarias. Tampoco implementa pagos, registro público de administradores o envío real de correos. La creación inicial de administradores se realiza mediante comando interactivo, sin contraseñas en el repositorio.

## Secuencia

1. Diagnóstico, rama y esquema aislado.
2. Rutas públicas y portada de acceso, con diseño existente.
3. Modelos, migraciones, panel y flujos editoriales.
4. Participación, contacto, multimedia, consentimiento y estadísticas.
5. Pruebas funcionales/seguridad, revisión responsive y documentación de operación.

El contenido institucional provisional solo se previsualiza en entorno local. Testimonios, fotografías y consentimientos nunca se publican por esa excepción. Para producción se necesitan textos y permisos revisados, responsable de datos, HTTPS, credenciales propias, copias de seguridad y correo autorizado si se desea habilitarlo.
