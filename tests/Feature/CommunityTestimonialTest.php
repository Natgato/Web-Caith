<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use App\Models\User;
use App\Services\CommunityText;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CommunityTestimonialTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_withdraw_a_published_story_and_its_photo(): void
    {
        Storage::fake('local');
        $writer = User::factory()->create();
        $this->actingAs($writer)->post('/cuenta/testimonio/editor', [
            'action' => 'submit', 'title' => 'Historia ficticia de prueba', 'public_name' => 'Firma ficticia',
            'body' => 'Un testimonio ficticio para comprobar la publicación y el retiro.',
            'allow_text' => 1, 'allow_image' => 1, 'photo_alt' => 'Una imagen de prueba',
            'photo' => UploadedFile::fake()->createWithContent('prueba.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/l9sAAAAASUVORK5CYII=')),
        ])->assertSessionHasNoErrors();
        $story = Testimonial::firstOrFail();
        $this->get('/cuenta/testimonio/'.$story->id.'/foto')->assertOk();
        $this->get('/historias/'.$story->slug.'/imagen')->assertNotFound();
        $this->get('/media/'.$story->media_id)->assertNotFound();

        $this->actingAs(User::factory()->create());
        $this->post('/cuenta/testimonio/'.$story->id.'/retirar', ['confirm' => 1])->assertNotFound();
        $this->get('/cuenta/testimonio/'.$story->id.'/foto')->assertNotFound();

        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $this->get('/admin/contenidos/testimonios/'.$story->id.'/editar')->assertOk();
        $this->post('/admin/testimonios/'.$story->id.'/consentimiento', [
            'details' => 'Autorización ficticia usada solo para verificar el flujo.',
            'allow_text' => 1, 'allow_image' => 1, 'involves_minor' => 0, 'guardian_verified' => 0, 'granted_at' => today()->toDateString(),
            'evidence' => UploadedFile::fake()->createWithContent('permiso.pdf', "%PDF-1.4\n1 0 obj << /Type /Catalog >> endobj\n%%EOF"),
            'consent_reviewed' => 1, 'review_version' => CommunityText::reviewVersion($story),
        ])->assertSessionHasNoErrors();
        $this->put('/admin/contenidos/testimonios/'.$story->id, [
            'status' => 'published', 'publication_reviewed' => 1, 'review_version' => CommunityText::reviewVersion($story),
        ])->assertSessionHasNoErrors();
        $this->get('/historias/'.$story->slug)->assertOk()->assertSee($story->title);
        $this->get('/historias/'.$story->slug.'/imagen')->assertOk();
        $this->get('/media/'.$story->media_id)->assertNotFound();

        $this->actingAs($writer);
        $this->get('/cuenta/testimonio/'.$story->id.'/retirar')->assertOk();
        $this->post('/cuenta/testimonio/'.$story->id.'/retirar', ['confirm' => 1])->assertSessionHasNoErrors();
        $this->get('/historias/'.$story->slug)->assertNotFound();
        $this->get('/historias/'.$story->slug.'/imagen')->assertNotFound();
        $this->assertNotNull($story->refresh()->consent->withdrawn_at);
    }
}
