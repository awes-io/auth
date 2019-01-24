<?php

namespace AwesIO\Auth\Tests\Feature;

use Mockery;
use AwesIO\Auth\Facades\Auth;
use AwesIO\Auth\Tests\TestCase;
use AwesIO\Auth\Tests\Stubs\User;

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
}