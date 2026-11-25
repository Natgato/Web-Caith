<?php

namespace App\Console\Commands;

use Database\Seeders\DemoPresentationSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class StartDemo extends Command
{
    protected $signature = 'caith:demo
        {--solo-semillas : Prepara los ejemplos y termina para usar Apache de XAMPP}
        {--host=127.0.0.1 : Dirección local del servidor}
        {--port=8000 : Puerto del servidor}';

    protected $description = 'Prepara una presentación local con datos ficticios y arranca la web';

    public function handle(): int
    {
        if (! app()->environment('local', 'testing')) {
            $this->error('La demostración requiere APP_ENV=local o testing.');

            return self::FAILURE;
        }

        $validator = Validator::make($this->options(), [
            'port' => ['required', 'integer', 'between:1,65535'],
            'host' => ['required', 'string', 'regex:/^(localhost|(?:\d{1,3}\.){3}\d{1,3})$/'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        config(['caith.demo_enabled' => true]);
        if ($this->call('migrate', ['--force' => true]) !== self::SUCCESS
            || $this->call('db:seed', ['--class' => DemoPresentationSeeder::class, '--force' => true]) !== self::SUCCESS) {
            return self::FAILURE;
        }

        $this->info('Presentación preparada. Todos los nuevos ejemplos están identificados como ficticios.');
        $this->table(['Acceso', 'Correo', 'Contraseña inicial'], [
            ['Administrador', config('caith.demo_email'), config('caith.demo_password')],
            ['Usuario', DemoPresentationSeeder::USER_EMAIL, DemoPresentationSeeder::USER_PASSWORD],
        ]);
        $this->line('Si una cuenta ya existía, se conserva su contraseña actual.');

        if ($this->option('solo-semillas')) {
            $this->info('Puedes abrir la web desde Apache de XAMPP.');

            return self::SUCCESS;
        }

        $url = 'http://'.$this->option('host').':'.$this->option('port');
        $this->info('Web '.$url.' · Administración '.$url.'/admin/login · Usuario '.$url.'/cuenta/entrar');
        $this->line('Ctrl+C detiene el servidor.');

        // Laravel serve forwards these overrides to its child PHP process.
        $overrides = [
            'APP_URL' => $url,
            'APP_DEBUG' => 'false',
            'CAITH_DEMO_ENABLED' => 'true',
            'CAITH_FORMS_ENABLED' => 'true',
            'CAITH_PREVIEW_INSTITUTIONAL' => 'true',
        ];
        $previous = [];
        foreach ($overrides as $key => $value) {
            $previous[$key] = $_ENV[$key] ?? null;
            $_ENV[$key] = $value;
        }

        try {
            return $this->call('serve', [
                '--host' => $this->option('host'),
                '--port' => $this->option('port'),
                '--tries' => 0,
                '--no-reload' => true,
            ]);
        } finally {
            foreach ($previous as $key => $value) {
                if ($value === null) {
                    unset($_ENV[$key]);
                } else {
                    $_ENV[$key] = $value;
                }
            }
        }
    }
}
