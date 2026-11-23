<?php

namespace App\Services;

use App\Models\User;

class DemoAccess
{
    public static function enabled(): bool
    {
        return (bool) config('caith.demo_enabled') && app()->environment('local', 'testing');
    }

    public function ensureAccount(): void
    {
        if (! self::enabled()) {
            return;
        }

        // A separate account leaves existing administrators and their passwords intact.
        $user = new User;
        $user->forceFill([
            'email' => config('caith.demo_email'),
            'name' => 'Administración de demostración',
            'password' => config('caith.demo_password'),
            'is_admin' => true,
        ]);
        User::unguarded(fn () => User::firstOrCreate(['email' => config('caith.demo_email')], $user->getAttributes()));
    }
}
