---
name: Casa CAITH — Memoria viva
description: Identidad editorial ilustrada y plataforma de gestión cálida y legible.
colors:
  wine: "#701f35"
  paper: "#f4ebdd"
  ink: "#30282a"
  muted: "#65534e"
  wine-deep: "#531426"
  soft: "#eadfd2"
  field-private: "#fffaf3"
typography:
  display:
    fontFamily: "Barlow, sans-serif"
    fontSize: "clamp(80px, 7.7vw, 120px)"
    fontWeight: 800
    lineHeight: 0.89
    letterSpacing: "-0.035em"
  headline:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "clamp(54px, 5.6vw, 84px)"
    fontWeight: 500
    lineHeight: 0.97
    letterSpacing: "-0.03em"
  body:
    fontFamily: "Barlow, sans-serif"
    fontSize: "18px"
    fontWeight: 400
    lineHeight: 1.6
  lead:
    fontFamily: "Barlow, sans-serif"
    fontSize: "24px"
    fontWeight: 400
    lineHeight: 1.45
  navigation:
    fontFamily: "Barlow, sans-serif"
    fontSize: "17px"
    fontWeight: 500
    lineHeight: 1.6
  history-control:
    fontFamily: "Barlow, sans-serif"
    fontSize: "24px"
    fontWeight: 400
    lineHeight: 1.6
  context:
    fontFamily: "Barlow, sans-serif"
    fontSize: "16px"
    fontWeight: 400
    lineHeight: 1.6
  fine-print:
    fontFamily: "Barlow, sans-serif"
    fontSize: "14px"
    fontWeight: 400
    lineHeight: 1.6
  wordmark:
    fontFamily: "Barlow, sans-serif"
    fontSize: "32px"
    fontWeight: 800
    lineHeight: 1
    letterSpacing: "-0.04em"
  hero-description:
    fontFamily: "Barlow, sans-serif"
    fontSize: "20px"
    fontWeight: 400
    lineHeight: 1.5
  scene-annotation:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "27px"
    fontWeight: 500
    lineHeight: 1.1
  page-title:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontSize: "clamp(44px, 6vw, 84px)"
    fontWeight: 500
    lineHeight: 1
    letterSpacing: "-0.035em"
  article:
    fontFamily: "Barlow, sans-serif"
    fontSize: "19px"
    fontWeight: 400
    lineHeight: 1.7
  entry-title:
    fontFamily: "Barlow, sans-serif"
    fontSize: "28px"
    fontWeight: 600
    lineHeight: 1.15
    letterSpacing: "-0.02em"
  admin-title:
    fontFamily: "Barlow, sans-serif"
    fontSize: "36px"
    fontWeight: 700
    lineHeight: 1.1
    letterSpacing: "-0.025em"
  admin-section:
    fontFamily: "Barlow, sans-serif"
    fontSize: "25px"
    fontWeight: 600
    lineHeight: 1.2
  field:
    fontFamily: "Barlow, sans-serif"
    fontSize: "18px"
    fontWeight: 400
    lineHeight: 1.4
  button:
    fontFamily: "Barlow, sans-serif"
    fontSize: "18px"
    fontWeight: 600
    lineHeight: 1.3
  history-year:
    fontFamily: "Barlow, sans-serif"
    fontSize: "clamp(120px, 14vw, 200px)"
    fontWeight: 800
    lineHeight: 0.9
    letterSpacing: "-0.04em"
spacing:
  gutter: "clamp(24px, 5.4vw, 88px)"
  section: "clamp(76px, 8vw, 116px)"
  columns: "9%"
  paragraph: "20px"
rounded:
  square: "0px"
components:
  hero-headline:
    textColor: "{colors.wine}"
    typography: "{typography.display}"
  poster-lettering:
    textColor: "{colors.paper}"
  community-illustration:
    textColor: "{colors.wine}"
    typography: "{typography.scene-annotation}"
    width: "52%"
  text-link:
    textColor: "{colors.wine}"
  navigation:
    textColor: "{colors.ink}"
    typography: "{typography.navigation}"
  history-control:
    textColor: "{colors.paper}"
    typography: "{typography.history-control}"
    padding: "5px 0"
  menu-toggle:
    textColor: "{colors.ink}"
    padding: "10px 0 10px 16px"
  footer:
    backgroundColor: "{colors.wine}"
    textColor: "{colors.paper}"
    padding: "54px 0 32px"
  editorial-entry:
    textColor: "{colors.ink}"
    padding: "8px 0"
  field:
    backgroundColor: "{colors.paper}"
    textColor: "{colors.ink}"
    typography: "{typography.field}"
    rounded: "{rounded.square}"
    padding: "12px 14px"
  button-primary:
    backgroundColor: "{colors.wine}"
    textColor: "{colors.paper}"
    typography: "{typography.button}"
    padding: "13px 22px"
  button-secondary:
    backgroundColor: "{colors.soft}"
    textColor: "{colors.wine}"
    typography: "{typography.button}"
    padding: "13px 22px"
  admin-sidebar:
    backgroundColor: "{colors.wine}"
    textColor: "{colors.paper}"
    padding: "32px 24px"
  dashboard-item:
    backgroundColor: "{colors.soft}"
    textColor: "{colors.ink}"
    padding: "26px"
  data-table:
    textColor: "{colors.ink}"
  feedback:
    backgroundColor: "{colors.soft}"
    textColor: "{colors.wine}"
    padding: "20px"
---

# Design System: Casa CAITH

## Overview

**Creative North Star: "Memoria viva"**

La memoria de una casa abierta toma forma de revista ilustrada: palabras contundentes, papel cálido con grano y escenas originales de convivencia en tinta y trama de impresión. La portada es un índice visual que invita a páginas independientes mediante accesos editoriales y vistas previas reales, sin repetir cuerpos completos.

La identidad pública es humana y expresiva; la administración es clara y operativa. Ambas comparten vino, papel y Barlow. Los personajes ilustrados son ficticios: no representan a beneficiarias reales ni documentan instalaciones. Los contenidos provisionales y los espacios todavía vacíos lo dicen con claridad.

**Key Characteristics:**
- Hero de cartel en Barlow 800, con serif editorial para lectura institucional.
- Vino protagonista, papel impreso y escenas conceptuales originales sin marco.
- Accesos públicos abiertos, con jerarquía de título, resumen y flecha.
- Panel de gestión con lateral vino, tablas, filtros y formularios.
- Estados veraces, fuentes visibles y consentimiento separado de publicación.

## Colors

Los valores del frontmatter son normativos; una sola familia cálida sirve a lectura, acción y gestión.

### Primary
- **Vino de memoria** (`wine`): titulares, marca, enlaces, botones principales y lateral administrativo.
- **Vino profundo** (`wine-deep`): hover de botón, acciones destructivas y sección activa del lateral.

### Neutral
- **Papel cálido** (`paper`): fondo público y texto invertido; en administración, fondo liso.
- **Tinta cálida** (`ink`): lectura, campos y datos.
- **Tierra tenue** (`muted`): ayudas, fuentes, estados y notas.
- **Papel de gestión** (`soft`): cabeceras de tabla, feedback, botones secundarios y tiles administrativos.
- **Campo privado** (`field-private`): fondo de campos en el editor administrativo.

El rose declarado en CSS no se usa como token de interfaz. Los acentos de las ilustraciones pertenecen a sus imágenes, no a nuevos colores funcionales.

## Typography

**Display Font:** Barlow para el hero; Cormorant Garamond con Georgia/serif para títulos públicos y anotaciones.
**Body Font:** Barlow con sans-serif como alternativa; fuentes locales con `font-display: swap`.

El hero usa mayúsculas de peso 800. Los títulos institucionales conservan la serif; el panel sustituye la escala de cartel por títulos Barlow prácticos. No trasladar tamaños de hero a formularios o tablas.

### Hierarchy
- **Hero:** `display`; dos líneas, Barlow 800, interlínea compacta.
- **Sección / página:** `headline` y `page-title`; encabezados de portada ajustan a `clamp(44px, 4.7vw, 68px)`.
- **Acceso editorial:** `entry-title`, Barlow 600; 25px en móvil.
- **Lectura:** `body` y `article`; artículo a 18px en móvil, máximo 70ch y títulos interiores Barlow 600 (28px).
- **Introducción:** `lead`; 22px a partir del ajuste compacto de 1050px.
- **Control / contexto:** `navigation`, `history-control`, `field`, `button`, `context` y `fine-print` conservan medidas propias. Fuentes, ayudas y estados usan 16px/1.5; tabla 16px y notas interiores 15px.
- **Gestión:** `admin-title` y `admin-section`; título principal baja a 30px en móvil. Tiles: encabezado 22px y conteo 44px, peso 700. Login: 46px/40px, Barlow 800.
- **Anotaciones:** `scene-annotation` en cursiva, 27px/24px/23px según anchura; no son acciones.
- **Marca / año:** `wordmark` y `history-year`; la cronología móvil usa año de 88px. El lettering contorneado usa 48px, 38px en tablet y 28px/26px en móvil pequeño.

## Layout

Contenedor público centrado de hasta 1600px, canal lateral `gutter` y ritmo vertical `section`. Páginas interiores: 44px arriba y 100px abajo, reducidos a 28px/72px en móvil. Lectura corta hasta 65ch; formularios públicos hasta 660px y administrativos hasta 760px.

La portada mantiene el hero aprobado y ofrece entradas de dos columnas (42px por 70px de separación), tres hitos y vistas previas de publicaciones, datos e historias autorizadas. En móvil las entradas y vistas previas pasan a una columna. Las páginas interiores desarrollan el contenido; la cronología interactiva vive en su propia ruta.

Hero: proporciones 1.15fr/.85fr; escena a la derecha al 52%, a 65px del pie. Hasta 1050px ocupa 57%; el lettering queda a la izquierda (7%, 95px desde abajo). Hasta 760px vuelve al flujo: lectura, fila de fecha/lettering, escena y anotaciones, sin colisiones. El titular responde a `clamp(60px, 7.5vw, 80px)` en tablet; `clamp(47px, 9.8vw, 72px)` con interlínea .91 en móvil; 42px hasta 380px.

La navegación pública se repliega a 960px, independientemente del cambio del hero a 760px. El panel tiene lateral de 260px, reducido a 225px hasta 1100px; hasta 960px se convierte en cabecera en flujo con enlaces envueltos. El contenido usa `min-width: 0`; tiles de dos columnas pasan a una hasta 760px. Tablas permiten desplazamiento horizontal; estadísticas mantiene 900px de anchura mínima para conservar legibilidad de indicadores y fuentes.

## Elevation & Depth

No hay sombras. La profundidad pública viene del papel raster repetido a 620px, tinta, tramas y escala tipográfica. El panel usa papel liso y superficies suaves para agrupar gestión; no simula flotación. Las bandas vino y el lateral definen orientación sin decoración adicional.

## Shapes

**The Open Public Composition Rule.** No encerrar imágenes ni secciones editoriales públicas en cajas, marcos o bordes.

Las escenas conservan transparencia, trazo irregular y bordes orgánicos, sin máscaras geométricas. Los accesos públicos son texto abierto. Campos con borde, tablas con separadores y tiles administrativos son controles y superficies de gestión solicitados; no trasladarlos como tarjetas a la portada. Sus esquinas son rectas, sin una escala de radios decorativos.

## Components

### Poster hero and illustration

Titular Barlow 800 y escena `caith-convivencia.png`: dos jóvenes ficticias y una educadora con libros, mesa y Cusco al fondo. `Aprender. Compartir. Decidir.` es un figcaption decorativo sin acciones, girado −7°, 4°, −3°. La imagen tiene alt descriptivo; los adornos son `aria-hidden` o alt vacío. Fecha 1994 girada 7°, tracking −.04em; lettering girado −8° con trazo vino, relleno papel. En móvil, ambos comparten una fila antes de la escena. No cubrir rostros ni palabras.

La ilustración de libro en tinta y el paisaje anteriores permanecen como activos disponibles, sin inventar usos en las nuevas plantillas. La portada utiliza escena, cantuta y papel. La procedencia está en los dos registros de assets de `.impeccable`.

### Editorial entries and previews

Acceso sin caja: título Barlow, resumen breve y flecha SVG. Hover subraya el título y desplaza la flecha 3px hacia arriba/derecha. No insertar cuerpos completos en portada. Vistas previas muestran únicamente publicaciones disponibles y datos reales con año, ámbito y fuente; estados vacíos explican qué falta sin números ni relatos ficticios.

### Navigation, CTA and footer

Navegación pública horizontal y menú Menú/Cerrar hasta 960px; `aria-current` subraya la sección. Escape cierra y devuelve foco. Sin JavaScript la navegación permanece visible. CTA textual vino, subrayado, flecha y altura mínima 48px; botones rellenos se usan cuando la tarea requiere acción. El pie incluye contacto, privacidad/retiro, donaciones próximamente y acceso administrativo, junto a la aclaración conceptual.

### Timeline and statistics

Selector de año sin borde ni fondo sobre vino: activo opaco y subrayado; teclado con flechas, Home y End. Sin JavaScript se leen todos los hitos. Los hitos provisionales se identifican. El bloque de cronología usa relleno 48px, 30px/22px en móvil.

Gráficos SVG horizontales con valor y año legibles y nombre accesible; tabla equivalente con caption, encabezados, ámbitos y fuentes verificadas. Los porcentajes usan base 100; no extrapolar series nacionales a Cusco ni presentar estos datos como impacto de CAITH. La tabla de estadísticas desplaza horizontalmente a partir de su mínimo de 900px; se informa al usuario.

### Fields, checkboxes and buttons

Etiquetas visibles y campos rectos con borde tierra de 1px, altura mínima 48px y padding 12px/14px. Textarea de al menos 130px, redimensionable verticalmente. Fondo papel en público y más claro en el editor privado. Foco vino de 2px, separado 3px; `aria-invalid` aumenta el borde a 2px y enlaza el error textual. Checkbox vino de 20px, junto a su descripción completa.

Botón principal vino/papel; hover vino profundo. Secundario papel de gestión/vino; destructivo vino profundo y texto explícito. Padding 13px/22px, altura mínima 48px. Estado deshabilitado con opacidad .6 y cursor de espera. Las solicitudes públicas se guardan para revisión, no equivalen a aceptación ni envío de correo; la revisión local lo comunica.

### Feedback and empty states

Éxito usa `role=status`; error usa `role=alert`, lista de problemas y mensajes junto al campo. Fondo suave, vino y padding 20px, reducido a 16px en móvil. Los estados editoriales, provisionales, vacíos y filtros sin resultados se expresan en texto, sin depender sólo del color. Donaciones sigue como próximamente; no hay pago operativo.

### Admin sidebar, tiles and tables

Lateral vino/papel, sección activa vino profundo, enlaces claros y salida de sesión textual. Títulos compactos, filtros por título/estado o referencia/estado, paginación Anterior/Siguiente y tablas con acciones explícitas. Cabecera suave, celdas con padding 16px y separador suave; los contenedores desplazan horizontalmente.

Los únicos tiles de conteo son los del dashboard: fondo suave, padding 26px, título, cantidad real y contexto de revisión cuando corresponde. Son registros de gestión de la web, nunca personas beneficiarias ni impacto institucional. No inventar cifras para una muestra.

### Protected consent workflow

El editor separa estado editorial, revisión de publicación y consentimiento protegido. Guardar o modificar consentimiento devuelve la historia a revisión; publicar es otro paso. Alcance de texto/nombre, imagen, representación aplicable y evidencia privada se muestran por separado. El retiro oculta la historia y su imagen. No mostrar evidencia privada en vistas públicas ni aplicar la vista local provisional a testimonios.

Contacto y participación incluyen aceptación de tratamiento y enlace de privacidad; la política sigue como documento de trabajo pendiente de validación. Las solicitudes de retiro/eliminación requieren revisión de legitimidad; actualizar su estado no anuncia correos ni eliminaciones automáticas. El editor diferencia Oculto/Archivado de Eliminar definitivamente.

### Shared interaction

Foco de enlaces/botones: 2px del color actual, offset 6px. Transiciones generales de 180ms; iconos 200–250ms. Sólo hero y cambio de relato tienen animación de entrada; no hay revelados generales por scroll. Movimiento reducido elimina animación, transiciones y scroll suave.

## Do's and Don'ts

### Do:
- **Do** conservar vino, papel, Barlow y Cormorant del frontmatter.
- **Do** mantener la portada como directorio con accesos abiertos y vistas previas reales.
- **Do** reservar superficies de gestión, tablas y filtros para las tareas que los necesitan.
- **Do** conservar etiquetas, foco, errores textuales y equivalentes de tabla para los gráficos.
- **Do** identificar contenidos provisionales, vacíos y escenas conceptuales con claridad.
- **Do** separar consentimiento, revisión y publicación, manteniendo privada la evidencia.

### Don't:
- **Don't** encerrar imágenes o secciones editoriales públicas en cajas, marcos o tarjetas.
- **Don't** añadir sombras ni revelados generales al hacer scroll.
- **Don't** inventar testimonios, cifras de impacto, contactos o contenido para llenar estados vacíos.
- **Don't** tratar conteos administrativos como datos de beneficiarias ni estadísticas nacionales como cifras de Cusco.
- **Don't** publicar historias sin consentimiento vigente ni exponer documentos privados.
- **Don't** presentar formularios como aceptación automática, correos enviados o pagos operativos.
