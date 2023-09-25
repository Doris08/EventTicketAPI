<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketType>
 */
class TicketTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $event = Event::factory()->create();

        return [
            'event_id' => $event->id,
            'name' =>  $this->faker->name(),
            'description' =>  $this->faker->sentence(),
            'quantity_available' => $this->faker->numberBetween(1, 20),
            'price' => $this->faker->numberBetween(1.0, 20.0),
            'sale_start_date' => now()->format('Y-m-d'),
            'sale_start_time' => now()->format('H:i'),
            'sale_end_date' =>  now()->addDays(1)->format('Y-m-d'),
            'sale_end_time' => now()->addHour(1)->format('H:i'),
            'purchase_limit' => $this->faker->numberBetween(1, 20),
       ];

    }
}
