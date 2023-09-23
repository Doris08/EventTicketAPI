<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $user = User::factory()->create();
        return [
            'organizer_id' => $user->id,
            'name' => fake()->name(),
            'description' => fake()->sentence(),
            'start_date' => '2023-08-08',
            'start_time' => '12:30',
            'end_date' => '2023-08-12',
            'end_time' => '23:59',
            'location' => fake()->address(),
            'image_header_url' => fake()->url(),
            'status' => 'Drafted',
        ];
    }
}
