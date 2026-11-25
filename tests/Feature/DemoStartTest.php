<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\Media;
use App\Models\Opinion;
use App\Models\ParticipationRequest;
use App\Models\Post;
use App\Models\Testimonial;
use App\Models\User;
use Database\Seeders\DemoPresentationSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DemoStartTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_seeds_once_preserves_edits_and_keeps_withdrawn_stories_hidden(): void
    {
        Storage::fake('local');
        $this->artisan('caith:demo', ['--solo-semillas' => true])->assertSuccessful();

        $this->assertSame(12, Post::published()->where('slug', 'like', 'demo-caith-%')->count());
        $this->assertDatabaseCount('opinions', 6);
        $this->assertDatabaseCount('testimonials', 5);
        $this->assertDatabaseCount('media', 4);
        $this->assertDatabaseCount('contact_messages', 18);
        $this->assertDatabaseCount('participation_requests', 18);
        $this->assertSame(18, ContactMessage::distinct()->count('reference'));
        $this->assertSame(18, ParticipationRequest::distinct()->count('reference'));
        $this->assertTrue(Media::firstOrFail()->isAuthorized());

        $user = User::where('email', DemoPresentationSeeder::USER_EMAIL)->firstOrFail();
        $this->assertFalse($user->is_admin);
        $this->assertTrue(Hash::check(DemoPresentationSeeder::USER_PASSWORD, $user->password));
        $user->update(['password' => 'ClaveEditadaDeLaCuenta!']);
        $post = Post::where('slug', 'demo-caith-noticia-1')->firstOrFail();
        $post->update(['title' => 'Título editado durante una presentación']);
        $story = Testimonial::where('slug', 'demo-caith-testimonio-1')->firstOrFail();
        $story->consent->update(['withdrawn_at' => now(), 'withdrawal_reason' => 'Retiro ficticio durante la demostración.']);
        $story->update(['status' => 'hidden']);

        $this->artisan('caith:demo', ['--solo-semillas' => true])->assertSuccessful();
        $this->assertDatabaseCount('posts', 14);
        $this->assertDatabaseCount('opinions', 6);
        $this->assertDatabaseCount('testimonials', 5);
        $this->assertDatabaseCount('consents', 3);
        $this->assertDatabaseCount('contact_messages', 18);
        $this->assertDatabaseCount('participation_requests', 18);
        $this->assertSame('Título editado durante una presentación', $post->fresh()->title);
        $this->assertTrue(Hash::check('ClaveEditadaDeLaCuenta!', $user->fresh()->password));
        $this->get('/historias/'.$story->slug)->assertNotFound();

        $visible = Testimonial::authorized()->firstOrFail();
        Storage::disk('local')->assertExists($visible->consent->evidence_path);
        $this->assertStringStartsWith('%PDF-1.4', Storage::disk('local')->get($visible->consent->evidence_path));
        $this->get('/noticias')->assertOk()->assertSee('Demo');
        $this->get('/opiniones')->assertOk()->assertSee('Firma ficticia');
        $this->get('/historias/'.$visible->slug)->assertOk()->assertSee('ficticio');
    }

    public function test_demo_refuses_production_before_modifying_data(): void
    {
        $this->app->instance('env', 'production');
        try {
            $this->artisan('caith:demo', ['--solo-semillas' => true])->assertFailed();
            $this->assertDatabaseCount('posts', 0);
            $this->assertDatabaseCount('users', 0);
        } finally {
            $this->app->instance('env', 'testing');
        }
    }
}
