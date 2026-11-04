<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdmin extends Command
{
    protected $signature = 'caith:admin {email? : Correo del administrador}';

    protected $description = 'Crea una cuenta administrativa mediante entrada local protegida';

    public function handle(): int
    {
        $email = strtolower(trim((string) ($this->argument('email') ?: $this->ask('Correo administrativo'))));
        $name = $this->ask('Nombre para el panel');
        $password = $this->secret('Contraseña (mínimo 12 caracteres, mayúsculas, minúsculas y números)');
        $confirmation = $this->secret('Repite la contraseña');
        $validator = Validator::make(['email' => $email, 'name' => $name, 'password' => $password, 'password_confirmation' => $confirmation], [
            'email' => ['required', 'email', 'max:180', 'unique:users,email'], 'name' => ['required', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Password::min(12)->mixedCase()->numbers()],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

return self::FAILURE;
        }
        $user = new User(['name' => $name, 'email' => $email, 'password' => $password]);
        $user->forceFill(['is_admin' => true])->save();
        $this->info('Cuenta creada. La contraseña se almacena con hash y no se muestra ni se escribe en archivos.');

        return self::SUCCESS;
    }
}
