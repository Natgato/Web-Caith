<?php

namespace App\Services;

use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\Statistic;
use App\Models\Testimonial;
use App\Models\TimelineEvent;

class EditorialRegistry
{
    public const STATES = ['draft' => 'Borrador', 'review' => 'En revisión', 'published' => 'Publicado', 'hidden' => 'Oculto', 'archived' => 'Archivado'];

    public static function modules(): array
    {
        $common = ['title' => ['Título', 'text'], 'slug' => ['Dirección breve', 'text']];
        $body = ['body' => ['Contenido', 'textarea'], 'source' => ['Fuente o procedencia', 'textarea']];

        return [
            'paginas' => ['label' => 'Páginas institucionales', 'model' => Page::class, 'fields' => $common + $body + ['is_provisional' => ['Contenido provisional', 'checkbox'], 'sort_order' => ['Orden', 'number']]],
            'publicaciones' => ['label' => 'Noticias y publicaciones', 'model' => Post::class, 'fields' => $common + ['category' => ['Categoría', 'text'], 'excerpt' => ['Resumen', 'textarea']] + $body + ['media_id' => ['Imagen autorizada', 'media']]],
            'testimonios' => ['label' => 'Historias y testimonios', 'model' => Testimonial::class, 'fields' => $common + ['public_name' => ['Nombre público o seudónimo autorizado', 'text'], 'testimony_date' => ['Fecha del testimonio', 'date']] + $body + ['media_id' => ['Fotografía autorizada', 'media']]],
            'galeria' => ['label' => 'Galería', 'model' => Media::class, 'fields' => $common + ['kind' => ['Tipo', 'select', ['photo' => 'Fotografía', 'video' => 'Video']], 'alt_text' => ['Descripción accesible', 'text'], 'external_url' => ['Video externo (HTTPS)', 'url'], 'source' => ['Autoría y procedencia', 'textarea'], 'authorization_reference' => ['Referencia privada de autorización', 'textarea'], 'authorization_expires_at' => ['Fin de autorización (opcional)', 'date']]],
            'cronologia' => ['label' => 'Línea de tiempo', 'model' => TimelineEvent::class, 'fields' => $common + ['year' => ['Año', 'number']] + $body + ['media_id' => ['Foto o video autorizado', 'media'], 'authorization_reference' => ['Autorización de publicación', 'textarea'], 'is_provisional' => ['Contenido provisional', 'checkbox'], 'sort_order' => ['Orden', 'number']]],
            'estadisticas' => ['label' => 'Estadísticas y fuentes', 'model' => Statistic::class, 'fields' => $common + ['indicator' => ['Indicador', 'text'], 'value' => ['Valor', 'number'], 'unit' => ['Unidad (%, personas…)', 'text'], 'year' => ['Año del dato', 'number'], 'geography' => ['Ámbito geográfico (Perú / Cusco…)', 'text'], 'source_url' => ['Enlace a fuente oficial (HTTPS)', 'url'], 'source_locator' => ['Tabla, página y notas metodológicas', 'text']] + $body],
        ];
    }

    public static function get(string $module): array
    {
        return self::modules()[$module] ?? abort(404);
    }
}
