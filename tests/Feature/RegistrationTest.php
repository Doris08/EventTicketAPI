<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function a_user_can_register()
    {

        $this->withoutExceptionHandling();

        $attrributes = [
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'company_name' => $this->faker->address,
            'email' => $this->faker->unique->safeEmail,
            'password' => 'password',
        ];

        $response = $this->post('api/register', $attrributes);

        array_pop($attrributes);
        $this->assertDatabaseHas('users', $attrributes);

        $response->assertStatus(201);
    }

    /** @test */
    public function a_user_can_login()
    {

        $this->withoutExceptionHandling();

        $email = $this->faker->unique->safeEmail;
        $password = 'password';

        $attrributes = [
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'company_name' => $this->faker->address,
            'email' => $email,
            'password' => $password,
        ];

        $response = $this->post('api/register', $attrributes);

        $user = [
            'email' => $email,
            'password' => $password,
        ];

        $response = $this->post('api/login', $user);

        $response->assertStatus(200);
    }
}
