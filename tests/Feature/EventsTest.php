<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Event;

class EventsTest extends TestCase
{
    use WithFaker; 
    /**
     * A basic feature test example.
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

    /** @test */
    /*public function a_event_requires_a_name(){
        $attrributes = Event::factory()->raw(['name' => '']);
        $response = $this->post('api/events/store', $attrributes)->assertSessionHasErrors('name');
    }*/

    /** @test */
    public function a_user_can_view_a_event(){

        $this->withoutExceptionHandling();

        $event = Event::factory()->create();
        
        $this->get('api/events/show/' . $event->id)
                ->assertStatus(200);
    }

    /** @test */
    public function a_user_can_publish_an_event(){

        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $event = Event::factory()->create();
        
        $this->patch('api/events/publish/'. $event->id)
                ->assertStatus(200);
    }
}
