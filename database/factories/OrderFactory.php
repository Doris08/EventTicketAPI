<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;
use App\Models\TicketType;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $event = Event::factory()->create();
        $ticketType = TicketType::factory()->create();

        return [
            'event_id' => $event->id,
            'purchase_date' =>  now()->format('Y-m-d'),
            'status' => 'Sold',
        ];

    }

}
