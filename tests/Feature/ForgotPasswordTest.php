<?php

namespace AwesIO\Auth\Tests\Feature;

use AwesIO\Auth\Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    /** @test */
    public function it_returns_view_on_index()
    {
        $this->get('password/reset')
            ->assertViewIs('awesio-auth::auth.passwords.email');
    }
}