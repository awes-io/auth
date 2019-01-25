<?php

namespace AwesIO\Auth\Tests\Feature;

use AwesIO\Auth\Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    /** @test */
    public function it_returns_view_on_password_request()
    {
        $this->get('password/reset')
            ->assertViewIs('awesio-auth::auth.passwords.email');
    }

    /** @test */
    public function it_returns_view_on_password_reset()
    {
        $this->get('password/reset/token')
            ->assertViewIs('awesio-auth::auth.passwords.reset');
    }
}