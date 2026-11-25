<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\EditorialRecord;
use App\Models\Media;
use App\Models\Opinion;
use App\Models\ParticipationRequest;
use App\Models\Post;
use App\Models\Testimonial;
use App\Models\User;
use App\Services\DemoAccess;
use Carbon\CarbonInterface;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DemoPresentationSeeder extends Seeder
{
    public const USER_EMAIL = 'usuario.demo@caith.test';
    public const USER_PASSWORD = 'UsuarioDemo2026!';

    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            throw new RuntimeException('Los ejemplos de presentación requieren un entorno local o testing.');
        }

        $this->call(InstitutionalSeeder::class);
        app(DemoAccess::class)->ensureAccount();
        $admin = User::where('email', config('caith.demo_email'))->where('is_admin', true)->firstOrFail();
        $user = User::firstOrCreate(['email' => self::USER_EMAIL], [
            'name' => 'Usuario de demostración',
            'password' => self::USER_PASSWORD,
        ]);
        if ($user->is_admin) {
            throw new RuntimeException('El correo del usuario demo está ocupado por una cuenta administrativa.');
        }

        $photos = [];
        foreach (config('stock') as $key => $stock) {
            $sourcePath = public_path('images/stock/'.$stock['file']);
            $destination = 'demo-caith/stock/'.$stock['file'];
            if (! Storage::disk('local')->exists($destination)
                && ! Storage::disk('local')->put($destination, file_get_contents($sourcePath))) {
                throw new RuntimeException('No se pudo guardar el recurso de demostración '.$stock['file']);
            }

            $photos[] = $this->record(Media::class, 'demo-caith-stock-'.$key, [
                'title' => $stock['title'].' · Demo',
                'kind' => 'photo',
                'path' => $destination,
                'mime_type' => 'image/jpeg',
                'size' => filesize($sourcePath),
                'alt_text' => $stock['alt'].' · Fotografía de stock.',
                'source' => $stock['author'].' · Pexels · '.$stock['source'],
                'authorization_reference' => 'Stock de demostración. Licencia y créditos en docs/FOTOS-STOCK.md.',
                ...$this->publication($admin),
            ]);
        }

        $evidence = 'demo-caith/consentimiento-ficticio-sin-validez-institucional.pdf';
        if (! Storage::disk('local')->exists($evidence)
            && ! Storage::disk('local')->put($evidence, $this->samplePdf())) {
            throw new RuntimeException('No se pudo guardar el documento de ejemplo.');
        }

        DB::transaction(function () use ($admin, $user, $photos, $evidence) {
            $topics = [
                ['Un encuentro entre libros y nuevas ideas', 'Educación', 'lectura y descubrimiento de nuevos aprendizajes'],
                ['Cocinar y aprender en compañía', 'Talleres', 'cocina y trabajo en equipo'],
                ['Un cuaderno para guardar lo aprendido', 'Vida cotidiana', 'organización de los estudios'],
                ['Tejer historias con las manos', 'Talleres', 'tejido y creatividad'],
                ['Compartir una tarde de conversación', 'Comunidad', 'escucha y convivencia'],
                ['El valor de preguntar y seguir aprendiendo', 'Educación', 'participación en el aprendizaje'],
                ['Pequeños acuerdos para convivir', 'Vida cotidiana', 'acuerdos cotidianos de convivencia'],
                ['Una mirada creativa a la fotografía', 'Talleres', 'expresión mediante fotografías'],
                ['Cuidar un espacio que compartimos', 'Comunidad', 'cuidado de los espacios comunes'],
                ['Descubrir nuevas formas de expresarse', 'Talleres', 'expresión artística'],
                ['Un momento para reconocer los avances', 'Educación', 'reflexión sobre los aprendizajes'],
                ['Ideas para una próxima actividad', 'Comunidad', 'propuestas y participación'],
            ];
            foreach ($topics as $index => [$title, $category, $topic]) {
                $when = now()->subDays(3 + $index * 5);
                $photo = $photos[$index % count($photos)];
                $this->record(Post::class, 'demo-caith-noticia-'.($index + 1), [
                    'title' => $title.' · Demo',
                    'category' => $category,
                    'excerpt' => 'Ejemplo ficticio sobre '.$topic.', preparado para presentar el diseño de Casa CAITH.',
                    'body' => "Publicación ficticia de demostración.\n\nEste ejemplo presenta una posible actividad de {$topic}. Permite mostrar cómo se organiza una noticia, su imagen, su categoría y su fecha.\n\nEn esta escena imaginada, las participantes comparten ideas, practican lo aprendido y encuentran nuevas preguntas para continuar el recorrido. La conversación y el trabajo compartido dan forma al relato.\n\nLa publicación reúne una introducción, el desarrollo de la experiencia y un cierre para invitar a seguir explorando los contenidos de la casa. El equipo puede editar estos párrafos desde el panel.\n\nEste contenido se creó para la presentación del proyecto. Las actividades y experiencias descritas son ficticias. La imagen es de stock y conserva sus créditos.\n\nFotografía de referencia: {$photo->source}",
                    'source' => 'Contenido ficticio de presentación. Fotografía de stock con crédito.',
                    'media_id' => $photo->id,
                    'created_at' => $when,
                    ...$this->publication($admin, $when),
                ]);
            }

            foreach (['draft' => 'Una noticia por completar', 'review' => 'Una actividad pendiente de revisión'] as $state => $title) {
                $this->record(Post::class, 'demo-caith-noticia-'.$state, [
                    'title' => $title.' · Demo', 'category' => 'Demostración',
                    'excerpt' => 'Ejemplo ficticio del flujo editorial.',
                    'body' => 'Contenido ficticio que permite mostrar los estados de borrador y revisión en el panel.',
                    'source' => 'Ejemplo ficticio de presentación.',
                    'author_id' => $admin->id, 'status' => $state,
                ]);
            }

            foreach ([
                'Aprender también es compartir',
                'Una idea para cuidar nuestros espacios',
                'La importancia de escuchar',
                'Preguntar abre nuevos caminos',
            ] as $index => $title) {
                $this->record(Opinion::class, 'demo-caith-opinion-'.($index + 1), [
                    'title' => $title.' · Demo', 'public_name' => 'Firma ficticia',
                    'body' => "## Una idea compartida\nEsta es una **opinión ficticia** creada para mostrar el editor y la lectura de publicaciones.\n> Cada experiencia puede abrir una conversación.\nEl texto permite ver párrafos, citas y subtítulos con la identidad visual de Casa CAITH.",
                    'source' => 'Opinión ficticia de presentación.',
                    'contributor_id' => $user->id,
                    'submitted_at' => now()->subDays(3 + $index),
                    ...$this->publication($admin, now()->subDays(2 + $index)),
                    'author_id' => $user->id,
                ]);
            }

            foreach (['published', 'published', 'published', 'review', 'draft'] as $index => $state) {
                $when = now()->subDays(4 + $index * 4);
                $story = $this->record(Testimonial::class, 'demo-caith-testimonio-'.($index + 1), [
                    'title' => ['Un pequeño aprendizaje', 'Mi cuaderno de ideas', 'Compartir lo que descubrimos', 'Un relato en revisión', 'Una historia por terminar'][$index].' · Demo',
                    'public_name' => 'Voz ficticia '.($index + 1),
                    'testimony_date' => $when->toDateString(),
                    'body' => "## Una historia de ejemplo\nEste **testimonio es completamente ficticio**. Se creó para presentar el diseño y los estados editoriales.\n> Aprender puede comenzar con una pregunta y continuar al compartirla.\nEn este relato imaginado, una persona descubre una nueva habilidad y la anota en su cuaderno. El texto muestra cómo puede organizarse una historia con párrafos y citas.\nLas personas y experiencias de este ejemplo son inventadas para la demostración.",
                    'source' => 'Testimonio ficticio de presentación con documento de ejemplo.',
                    'contributor_id' => $user->id,
                    'submitted_at' => $state === 'draft' ? null : $when,
                    ...($state === 'published' ? $this->publication($admin, $when) : ['status' => $state]),
                    'author_id' => $user->id,
                ]);
                if ($state === 'published') {
                    $story->consent()->firstOrCreate([], [
                        'details' => 'Documento ficticio para una historia ficticia. Uso exclusivo en presentación local.',
                        'allow_text' => true, 'allow_image' => false,
                        'involves_minor' => false, 'guardian_verified' => false,
                        'granted_at' => $when->toDateString(),
                        'evidence_path' => $evidence,
                        'reviewer_id' => $admin->id, 'reviewed_at' => $when,
                    ]);
                }
            }

            foreach (['draft', 'review'] as $state) {
                $this->record(Opinion::class, 'demo-caith-opinion-'.$state, [
                    'title' => ($state === 'draft' ? 'Mi opinión por terminar' : 'Una propuesta para revisar').' · Demo',
                    'public_name' => 'Firma ficticia',
                    'body' => 'Este borrador ficticio permite continuar la escritura y observar el proceso de revisión.',
                    'source' => 'Ejemplo ficticio de presentación.',
                    'author_id' => $user->id, 'contributor_id' => $user->id,
                    'submitted_at' => $state === 'review' ? now()->subDay() : null,
                    'status' => $state,
                ]);
            }

            foreach (range(0, 5) as $month) {
                foreach ([ContactMessage::class, ParticipationRequest::class] as $modelIndex => $model) {
                    $amounts = $modelIndex === 0 ? [3, 2, 4, 1, 5, 3] : [2, 4, 1, 5, 3, 3];
                    foreach (range(1, $amounts[$month]) as $item) {
                        $when = now()->startOfMonth()->subMonths($month)->addDays($item)->min(now());
                        $reference = sprintf('d3ca1700-0000-4000-8000-%012d', 100000000 * ($modelIndex + 1) + (int) $when->format('Ym') * 10 + $item);
                        $fields = [
                            'reference' => $reference,
                            'name' => 'Persona ficticia '.$item,
                            'email' => 'persona'.$item.'.demo@caith.test',
                            'phone' => null,
                            'message' => 'Solicitud ficticia de presentación. Se creó para mostrar la bandeja y el seguimiento administrativo.',
                            'kind' => $modelIndex === 0 ? 'consulta' : ['voluntariado', 'actividades', 'patrocinio'][($item - 1) % 3],
                            'status' => $month === 0 ? 'pending' : ($modelIndex === 0 ? 'resolved' : 'accepted'),
                            'consented_at' => $when,
                            'privacy_version' => config('caith.privacy_version'),
                            'internal_notes' => 'Registro ficticio de demostración.',
                            'created_at' => $when, 'updated_at' => $when,
                        ];
                        if ($modelIndex === 1) {
                            $fields['availability'] = 'Disponibilidad ficticia para la presentación.';
                        }
                        $model::unguarded(fn () => $model::firstOrCreate(['reference' => $reference], $fields));
                    }
                }
            }
        });
    }

    private function publication(User $admin, ?CarbonInterface $when = null): array
    {
        $when ??= now();

        return ['status' => 'published', 'author_id' => $admin->id, 'reviewer_id' => $admin->id, 'reviewed_at' => $when, 'published_at' => $when];
    }

    private function record(string $model, string $slug, array $fields): EditorialRecord
    {
        return $model::unguarded(fn () => $model::firstOrCreate(['slug' => $slug], $fields));
    }

    private function samplePdf(): string
    {
        $stream = "BT /F1 15 Tf 40 770 Td (EJEMPLO FICTICIO - DEMOSTRACION CAITH) Tj 0 -30 Td (Documento de prueba para una historia inventada.) Tj 0 -25 Td (Sin validez institucional. Uso local de demostracion.) Tj ET";
        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            '<< /Length '.strlen($stream)." >>\nstream\n".$stream."\nendstream",
        ];
        $pdf = "%PDF-1.4\n";
        $offsets = [0];
        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1)." 0 obj\n".$object."\nendobj\n";
        }
        $xref = strlen($pdf);
        $pdf .= "xref\n0 6\n0000000000 65535 f \n";
        foreach (array_slice($offsets, 1) as $offset) {
            $pdf .= sprintf("%010d 00000 n \n", $offset);
        }

        return $pdf."trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n".$xref."\n%%EOF\n";
    }
}
