<?php
namespace Database\Factories;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TourFactory extends Factory
{
    public function definition(): array
    {
        $titulos = [
            'Tour Minero por el Cerro Rico',
            'Recorrido Histórico Casa de Moneda',
            'Aventura en las Lagunas del Kari Kari',
            'Ruta Gastronómica Potosina',
            'Ascenso al Wayna Potosí',
            'Miradores Coloniales de Potosí',
            'Ruta de las Iglesias Coloniales',
            'Termas de Miraflores y Alrededores',
            'Paseo Nocturno Leyendas de Potosí',
            'Expedición Valle Hermoso',
            'Circuito Artesanal Minero',
            'Trekking Sendero del Inca Potosí'
        ];

        $titulo = $this->faker->unique()->randomElement($titulos) . ' ' . rand(1, 100);

        return [
            'categoria_id' => Categoria::inRandomOrder()->first()?->id ?? Categoria::factory(),
            'guia_id' => User::role('guia')->inRandomOrder()->first()?->id,
            'titulo' => $titulo,
            'slug' => Str::slug($titulo),
            'descripcion' => $this->faker->paragraph(3),
            'precio' => $this->faker->randomFloat(2, 50, 300),
            'duracion_horas' => $this->faker->numberBetween(2, 8),
            'cupo_maximo' => $this->faker->numberBetween(10, 30),
            'fecha_salida' => $this->faker->dateTimeBetween('+1 days', '+2 months'),
            'imagen' => null,
            'activo' => true,
        ];
    }
}