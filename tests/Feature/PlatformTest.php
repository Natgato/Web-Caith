<?php

namespace Tests\Feature;

use App\Models\Consent;
use App\Models\ContactMessage;
use App\Models\Media;
use App\Models\ParticipationRequest;
use App\Models\Post;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PlatformTest extends TestCase
{
    use RefreshDatabase;

    public function test_provisional_institutional_drafts_are_only_visible_in_local_preview(): void
    {
        $this->seed();
        config(['caith.preview_institutional' => true]);
        $this->get('/nosotros')->assertOk()->assertDontSee('CAITH significa');
        $this->app->instance('env', 'local');
        try {
            $this->get('/nosotros')->assertOk()->assertSee('CAITH significa')->assertSee('Contenido de trabajo');
        } finally {
            $this->app->instance('env', 'testing');
        }
    }

    public function test_initial_admin_command_creates_only_a_hashed_authorized_account(): void
    {
        $this->artisan('caith:admin', ['email' => 'admin-ficticio@example.test'])
            ->expectsQuestion('Nombre para el panel', 'Administrador ficticio')
            ->expectsQuestion('Contraseña (mínimo 12 caracteres, mayúsculas, minúsculas y números)', 'FicticiaSoloPrueba2026!')
            ->expectsQuestion('Repite la contraseña', 'FicticiaSoloPrueba2026!')
            ->assertSuccessful();
        $user = User::where('email', 'admin-ficticio@example.test')->firstOrFail();
        $this->assertTrue($user->is_admin);
        $this->assertNotSame('FicticiaSoloPrueba2026!', $user->getRawOriginal('password'));
    }

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        config(['caith.forms_enabled' => true]);
    }

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true, 'password' => 'FicticiaSoloPrueba2026!']);
    }

    private function content(array $extra = []): array
    {
        return [...['title' => 'Contenido ficticio para pruebas', 'slug' => 'contenido-de-prueba', 'body' => 'Texto de ejemplo ficticio para comprobar el editor sin usar datos personales.', 'source' => 'Fuente ficticia de prueba', 'status' => 'draft'], ...$extra];
    }

    private function image(): UploadedFile
    {
        return UploadedFile::fake()->createWithContent('prueba.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/l9sAAAAASUVORK5CYII='));
    }

    private function visitor(array $extra = []): array
    {
        return [...['name' => 'Persona ficticia de prueba', 'email' => 'persona@example.test', 'phone' => '+51 900 000 000', 'kind' => 'consulta', 'message' => 'Mensaje ficticio suficientemente largo para probar el formulario.', 'privacy_consent' => '1'], ...$extra];
    }

    private function story(User $admin): Testimonial
    {
        return Testimonial::create($this->content(['public_name' => 'Seudónimo de prueba', 'testimony_date' => '2020-01-01']));
    }

    private function consent(User $admin, Testimonial $story, array $extra = []): Consent
    {
        Storage::disk('local')->put('consents/prueba.pdf', '%PDF-1.4 documento ficticio');

        return $story->consent()->create([...['details' => 'Consentimiento ficticio solo para pruebas automáticas.', 'allow_text' => true, 'allow_image' => true, 'involves_minor' => false, 'guardian_verified' => false, 'granted_at' => '2020-01-01', 'reviewer_id' => $admin->id, 'reviewed_at' => now(), 'evidence_path' => 'consents/prueba.pdf'], ...$extra]);
    }

    public function test_all_public_pages_and_home_directory_work(): void
    {
        $this->seed();
        foreach (['/', '/nosotros', '/nosotros/mision-vision', '/nosotros/historia', '/nosotros/linea-de-tiempo', '/nuestra-labor', '/nuestra-labor/programas', '/nuestra-labor/actividades', '/nuestra-labor/impacto', '/realidad-social', '/realidad-social/trabajo-del-hogar', '/realidad-social/derechos-laborales', '/realidad-social/trata-y-explotacion', '/realidad-social/discriminacion', '/noticias', '/historias', '/galeria', '/estadisticas', '/como-ayudar', '/participa/voluntariado', '/participa/actividades', '/participa/patrocinio', '/contacto', '/privacidad', '/donaciones', '/explorar', '/admin/login'] as $url) {
            $this->get($url)->assertOk();
        }
        $this->get('/')->assertSee(route('nosotros'), false)->assertSee('caith-convivencia.png', false)->assertDontSee('id="proteccion"', false);
        $this->get('/donaciones')->assertSee('Próximamente')->assertDontSee('type="password"', false);
        $this->get('/register')->assertNotFound();
    }

    public function test_admin_requires_both_authentication_and_authorization(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));
        $this->post('/admin/contenidos/publicaciones', $this->content())->assertRedirect(route('admin.login'));
        $normal = User::factory()->create();
        $this->actingAs($normal)->get('/admin')->assertForbidden();
        $this->post('/admin/contenidos/publicaciones', $this->content())->assertForbidden();
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_admin_login_logout_and_normal_user_login_rejection(): void
    {
        $admin = $this->admin();
        $this->post('/admin/login', ['email' => $admin->email, 'password' => 'FicticiaSoloPrueba2026!'])->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
        $this->get('/admin')->assertOk();
        $this->post('/admin/logout')->assertRedirect(route('admin.login'));
        $this->assertGuest();
        $normal = User::factory()->create(['password' => 'FicticiaSoloPrueba2026!']);
        $this->post('/admin/login', ['email' => $normal->email, 'password' => 'FicticiaSoloPrueba2026!'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_is_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/admin/login', ['email' => 'no-existe@example.test', 'password' => 'incorrecta'])->assertSessionHasErrors('email');
        }
        $this->post('/admin/login', ['email' => 'no-existe@example.test', 'password' => 'incorrecta'])->assertStatus(429);
    }

    public function test_post_crud_publication_and_xss_escaping(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/admin/contenidos/publicaciones', $this->content(['category' => 'Prueba', 'excerpt' => 'Ejemplo ficticio']))->assertSessionHasNoErrors()->assertRedirect();
        $post = Post::firstOrFail();
        $this->get('/noticias/'.$post->slug)->assertNotFound();
        $payload = $this->content(['category' => 'Prueba', 'excerpt' => 'Ejemplo ficticio', 'body' => '<script>alert("prueba")</script>', 'status' => 'published', 'publication_reviewed' => '1']);
        $this->put('/admin/contenidos/publicaciones/'.$post->id, $payload)->assertSessionHasNoErrors();
        $this->get('/noticias/'.$post->slug)->assertOk()->assertSee('&lt;script&gt;', false)->assertDontSee('<script>alert(', false);
        $this->get('/admin/contenidos/publicaciones?q=Contenido&status=published')->assertOk()->assertSee($post->title);
        $this->delete('/admin/contenidos/publicaciones/'.$post->id)->assertRedirect();
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_publication_requires_review_source_and_valid_slug(): void
    {
        $this->actingAs($this->admin());
        $this->post('/admin/contenidos/publicaciones', $this->content(['category' => 'Prueba', 'status' => 'published']))->assertSessionHasErrors('publication_reviewed');
        $this->post('/admin/contenidos/publicaciones', $this->content(['slug' => '../ruta', 'category' => 'Prueba']))->assertSessionHasErrors('slug');
        $this->post('/admin/contenidos/publicaciones', $this->content(['source' => null, 'status' => 'published', 'publication_reviewed' => 1, 'category' => 'Prueba']))->assertSessionHasErrors('source');
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_pages_and_timeline_are_manageable_with_sources(): void
    {
        $this->actingAs($this->admin());
        $this->post('/admin/contenidos/paginas', $this->content(['is_provisional' => 0, 'sort_order' => 0, 'status' => 'published', 'publication_reviewed' => 1]))->assertSessionHasNoErrors();
        $this->get('/paginas/contenido-de-prueba')->assertOk()->assertSee('Texto de ejemplo');
        $this->post('/admin/contenidos/cronologia', $this->content(['year' => 1994, 'is_provisional' => 0, 'sort_order' => 0, 'authorization_reference' => 'Referencia ficticia para probar publicación', 'status' => 'published', 'publication_reviewed' => 1]))->assertSessionHasNoErrors();
        $this->get('/nosotros/linea-de-tiempo')->assertOk()->assertSee('1994')->assertSee('Fuente ficticia');
        $this->get('/admin/contenidos/no-existe')->assertNotFound();
    }

    public function test_testimonials_cannot_be_published_without_consent(): void
    {
        $admin = $this->admin();
        $story = $this->story($admin);
        $this->actingAs($admin)->put('/admin/contenidos/testimonios/'.$story->id, $this->content(['public_name' => 'Seudónimo de prueba', 'testimony_date' => '2020-01-01', 'status' => 'published', 'publication_reviewed' => 1]))->assertSessionHasErrors('status');
        $this->get('/historias/'.$story->slug)->assertNotFound();
        $this->get('/historias')->assertDontSee('Contenido ficticio para pruebas');
    }

    public function test_consent_is_private_encrypted_and_republication_is_explicit(): void
    {
        $admin = $this->admin();
        $story = $this->story($admin);
        $this->actingAs($admin)->post('/admin/testimonios/'.$story->id.'/consentimiento', ['details' => 'Documento ficticio de autorización usado solamente en una prueba.', 'allow_text' => 1, 'allow_image' => 0, 'involves_minor' => 0, 'guardian_verified' => 0, 'granted_at' => '2020-01-01', 'evidence' => UploadedFile::fake()->createWithContent('permiso.pdf', "%PDF-1.4\n1 0 obj << /Type /Catalog >> endobj\n%%EOF"), 'consent_reviewed' => 1])->assertSessionHasNoErrors();
        $consent = $story->fresh()->consent;
        $this->assertNotNull($consent);
        $this->assertStringNotContainsString('Documento ficticio', DB::table('consents')->value('details'));
        $this->assertSame('review', $story->fresh()->status);
        $this->get('/historias/'.$story->slug)->assertNotFound();
        $this->get('/admin/testimonios/'.$story->id.'/evidencia')->assertOk()->assertHeader('cache-control', 'no-store, private');
        auth()->logout();
        $this->get('/admin/testimonios/'.$story->id.'/evidencia')->assertRedirect(route('admin.login'));
        $this->get('/storage/'.$consent->evidence_path)->assertNotFound();
    }

    public function test_minor_consent_requires_verified_representation(): void
    {
        $admin = $this->admin();
        $story = $this->story($admin);
        $this->actingAs($admin)->post('/admin/testimonios/'.$story->id.'/consentimiento', ['details' => 'Autorización ficticia para probar reglas de representación.', 'allow_text' => 1, 'allow_image' => 0, 'involves_minor' => 1, 'guardian_verified' => 0, 'granted_at' => '2020-01-01', 'evidence' => UploadedFile::fake()->createWithContent('permiso.pdf', '%PDF-1.4 prueba'), 'consent_reviewed' => 1])->assertSessionHasErrors('guardian_verified');
        $this->assertDatabaseCount('consents', 0);
    }

    public function test_authorized_story_withdrawal_blocks_text_and_photograph(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/admin/contenidos/galeria', $this->content(['kind' => 'photo', 'alt_text' => 'Imagen ficticia de prueba', 'authorization_reference' => 'Permiso ficticio', 'asset_file' => $this->image(), 'status' => 'published', 'publication_reviewed' => 1]))->assertSessionHasNoErrors();
        $media = Media::firstOrFail();
        $story = $this->story($admin);
        $this->consent($admin, $story);
        $this->put('/admin/contenidos/testimonios/'.$story->id, $this->content(['public_name' => 'Seudónimo de prueba', 'testimony_date' => '2020-01-01', 'media_id' => $media->id, 'status' => 'published', 'publication_reviewed' => 1]))->assertSessionHasNoErrors();
        $this->get('/historias/'.$story->slug)->assertOk()->assertDontSee('Consentimiento ficticio');
        $this->get('/historias/'.$story->slug.'/imagen')->assertOk();
        $this->get('/media/'.$media->id)->assertNotFound();
        $this->post('/admin/testimonios/'.$story->id.'/retirar', ['withdrawal_reason' => 'Retiro ficticio solicitado para la prueba.'])->assertSessionHasNoErrors();
        $this->get('/historias/'.$story->slug)->assertNotFound();
        $this->get('/historias/'.$story->slug.'/imagen')->assertNotFound();
        $this->delete('/admin/contenidos/testimonios/'.$story->id)->assertRedirect();
        $this->get('/media/'.$media->id)->assertNotFound();
        Storage::disk('local')->assertMissing('consents/prueba.pdf');
    }

    public function test_expired_or_withdrawn_consent_is_never_public(): void
    {
        $admin = $this->admin();
        $story = $this->story($admin);
        $consent = $this->consent($admin, $story, ['expires_at' => '2020-12-31']);
        $story->forceFill(['status' => 'published', 'reviewed_at' => now(), 'published_at' => now()])->save();
        $this->get('/historias/'.$story->slug)->assertNotFound();
        $consent->update(['expires_at' => null, 'withdrawn_at' => now()]);
        $this->get('/historias/'.$story->slug)->assertNotFound();
    }

    public function test_gallery_upload_filter_expiry_and_executable_rejection(): void
    {
        $this->actingAs($this->admin());
        $this->post('/admin/contenidos/galeria', $this->content(['kind' => 'photo', 'alt_text' => 'Imagen ficticia', 'asset_file' => UploadedFile::fake()->createWithContent('ataque.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>')]))->assertSessionHasErrors('asset_file');
        $this->post('/admin/contenidos/galeria', $this->content(['kind' => 'photo', 'alt_text' => 'Imagen ficticia', 'asset_file' => $this->image(), 'status' => 'published', 'publication_reviewed' => 1, 'authorization_reference' => 'Permiso ficticio']))->assertSessionHasNoErrors();
        $media = Media::firstOrFail();
        $this->get('/galeria?tipo=photo')->assertOk()->assertSee($media->title);
        $this->get('/galeria?tipo=video')->assertDontSee($media->title);
        $this->get('/media/'.$media->id)->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff');
        $media->update(['authorization_expires_at' => now()->subDay()]);
        $this->get('/media/'.$media->id)->assertNotFound();
        $this->get('/galeria')->assertDontSee($media->title);
    }

    public function test_participation_persists_minimal_encrypted_data_and_rejects_overposting(): void
    {
        Mail::fake();
        $data = $this->visitor(['kind' => 'voluntariado', 'availability' => 'Sábados por la mañana, ejemplo ficticio', 'status' => 'accepted', 'assigned_to' => 1, 'is_admin' => 1]);
        $this->post('/participa', $data)->assertSessionHasNoErrors()->assertRedirect(route('participation.form', 'voluntariado'));
        $record = ParticipationRequest::firstOrFail();
        $this->assertSame('pending', $record->status);
        $this->assertNull($record->assigned_to);
        $this->assertSame($data['email'], $record->email);
        $this->assertNotSame($data['email'], DB::table('participation_requests')->value('email'));
        Mail::assertNothingSent();
    }

    public function test_forms_require_consent_availability_and_server_validation(): void
    {
        $this->post('/contacto', $this->visitor(['privacy_consent' => 0, 'email' => 'no-es-correo']))->assertSessionHasErrors(['privacy_consent', 'email']);
        $this->post('/participa', $this->visitor(['kind' => 'voluntariado']))->assertSessionHasErrors('availability');
        $this->post('/contacto', $this->visitor(['website' => 'spam']))->assertSessionHasErrors('website');
        $this->assertDatabaseCount('contact_messages', 0);
        $this->assertDatabaseCount('participation_requests', 0);
    }

    public function test_visitor_forms_are_rate_limited(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/contacto', $this->visitor())->assertSessionHasNoErrors();
        }
        $this->post('/contacto', $this->visitor())->assertStatus(429);
        $this->assertDatabaseCount('contact_messages', 5);
    }

    public function test_privacy_request_admin_review_and_data_deletion(): void
    {
        $this->post('/contacto', $this->visitor(['kind' => 'eliminacion']))->assertSessionHasNoErrors();
        $record = ContactMessage::firstOrFail();
        $this->get('/admin/bandeja/contacto/'.$record->id)->assertRedirect(route('admin.login'));
        $this->actingAs($this->admin())->get('/admin/bandeja/contacto/'.$record->id)->assertOk()->assertSee($record->email);
        $this->put('/admin/bandeja/contacto/'.$record->id, ['status' => 'resolved', 'internal_notes' => 'Verificación ficticia de prueba.'])->assertSessionHasNoErrors();
        $this->assertSame('resolved', $record->fresh()->status);
        $this->delete('/admin/bandeja/contacto/'.$record->id)->assertRedirect();
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_statistics_have_geography_source_year_and_accessible_chart(): void
    {
        $this->seed();
        $this->get('/estadisticas')->assertOk()->assertSee('Perú')->assertSee('2019')->assertSee('2022')->assertSee('Tabla 42')->assertSee('role="img"', false)->assertSee('class="statistics-table"', false);
        $this->get('/estadisticas?ambito=Cusco')->assertDontSee('420 328')->assertSee('No hay indicadores verificados');
        $this->actingAs($this->admin())->post('/admin/contenidos/estadisticas', $this->content(['indicator' => 'Indicador ficticio', 'value' => 120, 'unit' => '%', 'year' => 2020, 'geography' => 'Ámbito ficticio', 'source_url' => 'https://example.test/informe', 'status' => 'published', 'publication_reviewed' => 1]))->assertSessionHasErrors('value');
    }

    public function test_admin_editors_and_inboxes_render(): void
    {
        $this->actingAs($this->admin());
        foreach (['paginas', 'publicaciones', 'testimonios', 'galeria', 'cronologia', 'estadisticas'] as $module) {
            $this->get('/admin/contenidos/'.$module)->assertOk();
            $this->get('/admin/contenidos/'.$module.'/nuevo')->assertOk();
        }
        $this->get('/admin/bandeja/participacion')->assertOk();
        $this->get('/admin/bandeja/contacto')->assertOk();
    }

    public function test_production_forms_can_be_disabled_and_headers_are_present(): void
    {
        config(['caith.forms_enabled' => false]);
        $this->post('/contacto', $this->visitor())->assertStatus(503);
        $this->get('/')->assertHeader('X-Content-Type-Options', 'nosniff')->assertHeader('X-Frame-Options', 'DENY')->assertHeader('Content-Security-Policy');
        $this->get('/admin/login')->assertHeader('Cache-Control', 'no-store, private');
    }

    public function test_passwords_are_hashed_and_admin_flag_is_not_mass_assignable(): void
    {
        $user = new User(['name' => 'Prueba', 'email' => 'prueba@example.test', 'password' => 'ClaveFicticia2026!', 'is_admin' => true]);
        $user->save();
        $this->assertFalse($user->fresh()->is_admin);
        $this->assertNotSame('ClaveFicticia2026!', DB::table('users')->value('password'));
    }
}
