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
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'start_date' => now()->format('Y-m-d'),
            'start_time' => now()->format('H:i'),
            'end_date' => now()->addDays(1)->format('Y-m-d'),
            'end_time' => now()->addHour(1)->format('H:i'),
            'location' => $this->faker->address(),
            'image_header_url' => $this->faker->url(),
        ];
    }
}