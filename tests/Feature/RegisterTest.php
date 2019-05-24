<?php

namespace AwesIO\Auth\Tests\Feature;

use AwesIO\Auth\Tests\TestCase;
use Illuminate\Support\Facades\Event;

class RegisterTest extends TestCase
{
    /** @test */
    public function it_returns_login_view()
    {
        $this->get('register')
            ->assertViewIs('awesio-auth::auth.register');
    }

    /** @test */
    public function name_is_required()
    {
        $this->json('POST', 'register')
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function email_is_required()
    {
        $this->json('POST', 'register')
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function password_is_required()
    {
        $this->json('POST', 'register')
            ->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function it_can_register_user()
    {
        Event::fake();
        
        $this->post('register', [
            'name' => $name = uniqid(),
            'email' => 'email@email.com',
            'password' => $password = uniqid(),
            'password_confirmation' => $password,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => 1,
            'name' => $name,
        ]);
    }
}