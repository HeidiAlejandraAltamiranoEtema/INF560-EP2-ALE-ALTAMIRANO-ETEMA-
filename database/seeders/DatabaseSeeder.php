<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Categoria;
use App\Models\Servicio;
use App\Models\Tour;
use App\Models\Reserva;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $this->call(RoleSeeder::class);

        // 2. Categorías fijas
        $categorias = ['Histórico-cultural', 'Minero', 'Naturaleza', 'Gastronómico', 'Aventura'];
        foreach ($categorias as $cat) {
            Categoria::firstOrCreate(
                ['slug' => Str::slug($cat)],
                ['nombre' => $cat, 'descripcion' => "Tours de la categoría {$cat} en Potosí."]
            );
        }

        // 3. Servicios fijos
        $servicios = ['Transporte', 'Almuerzo', 'Entradas incluidas', 'Guía bilingüe', 'Seguro', 'Equipo de protección'];
        foreach ($servicios as $serv) {
            Servicio::firstOrCreate(['nombre' => $serv]);
        }

        // 4. Usuarios de prueba (contraseña por defecto: password)
        $admin = User::firstOrCreate(['email' => 'admin@turipotosi.test'], [
            'name' => 'Administrador TuriPotosí',
            'password' => bcrypt('password')
        ]);
        $admin->assignRole('admin');

        $guia1 = User::firstOrCreate(['email' => 'guia1@turipotosi.test'], [
            'name' => 'Guía Juan Pérez',
            'password' => bcrypt('password')
        ]);
        $guia1->assignRole('guia');

        $guia2 = User::firstOrCreate(['email' => 'guia2@turipotosi.test'], [
            'name' => 'Guía María López',
            'password' => bcrypt('password')
        ]);
        $guia2->assignRole('guia');

        $turista1 = User::firstOrCreate(['email' => 'turista1@turipotosi.test'], [
            'name' => 'Turista Pedro Gómez',
            'password' => bcrypt('password')
        ]);
        $turista1->assignRole('turista');

        $turista2 = User::firstOrCreate(['email' => 'turista2@turipotosi.test'], [
            'name' => 'Turista Ana Torres',
            'password' => bcrypt('password')
        ]);
        $turista2->assignRole('turista');

        // 5. Tours y servicios asociados
        Tour::factory(12)->create()->each(function ($tour) {
            $tour->servicios()->attach(Servicio::inRandomOrder()->take(rand(2, 4))->pluck('id'));
        });

        // 6. Reservas
        Reserva::factory(20)->create();
    }
}