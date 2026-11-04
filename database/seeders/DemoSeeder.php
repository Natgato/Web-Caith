<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new \RuntimeException('Los ejemplos se permiten solo en local o testing.');
        }
        Post::firstOrCreate(['slug' => 'ejemplo-ficticio-de-publicacion'], ['title' => 'Ejemplo ficticio para probar el editor', 'category' => 'Pruebas', 'excerpt' => 'Registro de prueba, no actividad institucional real.', 'body' => 'Este contenido es ficticio y solo permite comprobar la edición y los estados. No debe publicarse como una actividad real de CAITH.', 'source' => 'Datos ficticios de desarrollo', 'status' => 'draft']);
    }
}
