<?php

namespace AwesIO\Auth\Tests\Feature;

use AwesIO\Auth\Tests\TestCase;

class TwoFactorLoginTest extends TestCase
{
    /** @test */
    public function it_returns_view_on_index()
    {
        $this->get('login/twofactor/verify')
            ->assertViewIs('awesio-auth::twofactor.verify');
    }
}