<?php

namespace Database\Seeders;

use App\Services\DemoAccess;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(InstitutionalSeeder::class);
        app(DemoAccess::class)->ensureAccount();
    }
}
