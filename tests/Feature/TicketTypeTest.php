<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\TicketType;
use App\Models\User;

class TicketTypeTest extends TestCase
{
    use WithFaker;

    /**
     * Test for listing ticket types, only if the user is authenticated.
     */
    /** @test */
    public function index_ticket_type_requires_authentication()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $this->store_ticket_type_requires_authentication();

        $response = $this->get('api/ticket_types/index');

        $response->assertStatus(200);

    }

    /**
     * Testing for creating a ticket type, only if the user is authenticated.
     */
    /** @test */
    public function store_ticket_type_requires_authentication()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $ticketType = TicketType::factory()->create();
        $ticketType = $ticketType->toArray();

        $response = $this->post('api/ticket_types/store', $ticketType);
        $this->assertDatabaseHas('ticket_types', $ticketType);

        $response->assertStatus(201);

    }

    /**
     * Testing for creating a ticket type, only if the user is authenticated.
     */
    /** @test */
    public function show_ticket_type_requires_authentication()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);
        $ticketType = TicketType::factory()->create();

        $response = $this->get('api/ticket_types/show/'. $ticketType->id);

        $response->assertStatus(200);
    }

    /**
     * Testing for updating a ticket type, only if the user is authenticated.
     */
    /** @test */
    public function update_ticket_type_requires_authentication()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $ticketType = TicketType::factory()->create();
        $dataUpdate = [
            'name' =>  $this->faker->name(),
            'description' =>  $this->faker->sentence(),
            'quantity_available' => $this->faker->numberBetween(1, 20),
            'price' => $this->faker->numberBetween(1.0, 20.0),
            'sale_start_date' => now()->format('Y-m-d'),
            'sale_start_time' => now()->format('H:i'),
            'sale_end_date' =>  now()->addDays(1)->format('Y-m-d'),
            'sale_end_time' => now()->addHour(1)->format('H:i'),
            'purchase_limit' => 150,
        ];
        $response = $this->patch('api/ticket_types/update/' . $ticketType->id, $dataUpdate);

        //dd($response);

        //$this->assertDatabaseHas('ticket_types', $dataUpdate);

        $response->assertStatus(200);
    }

    /**
     * Testing for deleting a ticket type, only if the user is authenticated.
     */
    /** @test */
    public function delete_ticket_type_requires_authentication()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);
        $ticketType = TicketType::factory()->create();

        $response = $this->delete('api/ticket_types/delete/'. $ticketType->id);

        $response->assertStatus(200);
    }
}
