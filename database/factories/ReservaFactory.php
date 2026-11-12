<?php
namespace Database\Factories;

use App\Models\Tour;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::role('turista')->inRandomOrder()->first()?->id ?? User::factory(),
            'tour_id' => Tour::inRandomOrder()->first()?->id ?? Tour::factory(),
            'cantidad_personas' => $this->faker->numberBetween(1, 4),
            'estado' => $this->faker->randomElement(['pendiente', 'confirmada', 'cancelada']),
            'observacion' => $this->faker->optional()->sentence(),
        ];
    }
}