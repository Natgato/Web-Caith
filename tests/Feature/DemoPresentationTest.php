<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\DemoAccess;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DemoPresentationTest extends TestCase
{
    use RefreshDatabase;

    public function test_opening_login_provisions_a_working_hashed_demo_account_once(): void
    {
        config(['caith.demo_enabled' => true]);
        $this->get('/admin/login')->assertOk()
            ->assertSee('demo@caith.test')->assertSee('CaithDemo2026!');
        $user = User::where('email', 'demo@caith.test')->firstOrFail();
        $this->assertTrue($user->is_admin);
        $this->assertTrue(Hash::check('CaithDemo2026!', $user->getRawOriginal('password')));
        $originalHash = $user->getRawOriginal('password');
        $this->get('/admin/login')->assertOk();
        $this->assertDatabaseCount('users', 1);
        $this->assertSame($originalHash, $user->fresh()->getRawOriginal('password'));
        $this->post('/admin/login', ['email' => 'demo@caith.test', 'password' => 'CaithDemo2026!'])
            ->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->get('/admin')->assertOk();
    }

    public function test_demo_does_not_reset_an_existing_account(): void
    {
        $user = User::factory()->create(['email' => 'demo@caith.test', 'password' => 'ChangedPassword2026!']);
        app(DemoAccess::class)->ensureAccount();
        $this->assertTrue(Hash::check('ChangedPassword2026!', $user->fresh()->getRawOriginal('password')));
        $this->assertFalse($user->fresh()->is_admin);
    }

    public function test_disabled_and_production_modes_hide_and_do_not_create_demo_access(): void
    {
        config(['caith.demo_enabled' => false]);
        $this->get('/admin/login')->assertOk()->assertDontSee('CaithDemo2026!');
        $this->get('/galeria')->assertOk()->assertDontSee('images/stock/');
        $this->assertDatabaseCount('users', 0);
        config(['caith.demo_enabled' => true]);
        $this->app->instance('env', 'production');
        try {
            $this->get('/admin/login')->assertOk()->assertDontSee('CaithDemo2026!');
            $this->get('/galeria')->assertOk()->assertDontSee('images/stock/');
            $this->assertDatabaseCount('users', 0);
        } finally {
            $this->app->instance('env', 'testing');
        }
    }

    public function test_stock_is_labelled_and_does_not_fabricate_editorial_records(): void
    {
        config(['caith.demo_enabled' => true]);
        $this->get('/galeria')->assertOk()->assertSee('images/stock/biblioteca.jpg')
            ->assertSee('Stock de demostración')->assertSee('No es una fotografía de CAITH.');
        $this->get('/galeria?tipo=video')->assertOk()->assertDontSee('images/stock/');
        $this->assertDatabaseCount('media', 0);
        $this->assertDatabaseCount('testimonials', 0);
        $this->assertDatabaseCount('posts', 0);
    }
}
