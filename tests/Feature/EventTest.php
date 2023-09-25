<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\TicketType;

class EventTest extends TestCase
{
    use WithFaker;

    /**
     * Tets validating that a Event requires a name.
     */
    /** @test */
    public function a_event_requires_a_name()
    {

        $user = User::factory()->create();
        $this->actingAs($user);

        $attrributes = Event::factory()->create(['name' => '']);

        $response = $this->post('api/events/store', $attrributes->toArray());

        $response->assertStatus(422);
    }

    /**
     * Tets for listing events.
     */
    /** @test */
    public function a_user_can_list_events()
    {

        $this->withoutExceptionHandling();

        $event = Event::factory()->count(5)->create();

        $this->get('api/events/index')
                ->assertStatus(200);
    }

    /**
     * Tets for creating an event only if the user is authenticated.
     */
    /** @test */
    public function store_events_requires_authentication()
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $event = Event::factory()->raw();

        $response = $this->post('api/events/store', $event);

        array_shift($event);
        $this->assertDatabaseHas('events', $event);

        $response->assertStatus(201);
    }

    /**
     * Tets for showing an event only if the user is authenticated.
     */
    /** @test */
    public function a_user_can_view_an_event()
    {

        $this->withoutExceptionHandling();

        $event = Event::factory()->create();

        $this->get('api/events/show/' . $event->id)
                ->assertStatus(200);
    }

    /**
     * Tets for updating an event only if the user is authenticated.
     */
    /** @test */
    public function update_events_requires_authentication()
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $event = Event::factory()->create();

        $data = [
            'name' => $this->faker->name(),
            'description' => $this->faker->sentence(),
            'start_date' => now()->format('Y-m-d'),
            'start_time' => now()->format('H:i'),
            'end_date' => now()->addDays(1)->format('Y-m-d'),
            'end_time' => now()->addHour(1)->format('H:i'),
            'location' => $this->faker->address(),
            'image_header_url' => $this->faker->url(),
        ];


        $response = $this->patch('api/events/update/' . $event->id, $data);

        $this->assertDatabaseHas('events', $data);

        $response->assertStatus(200);
    }

    /**
     * Tets for showing an event only if the user is authenticated.
     */
    /** @test */
    public function delete_an_event_requires_authentication()
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $event = Event::factory()->create();

        $this->delete('api/events/delete/' . $event->id)
                ->assertStatus(200);
    }

    /**
     * Tets for showing an event only if the user is authenticated.
     */
    /** @test */
    public function a_user_can_publish_an_event()
    {

        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $ticketType = TicketType::factory()->create();

        $this->patch('api/events/publish/'. $ticketType->event_id)
                ->assertStatus(200);
    }
}
