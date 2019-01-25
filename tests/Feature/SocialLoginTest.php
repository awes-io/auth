<?php

namespace AwesIO\Auth\Tests\Feature;

use AwesIO\Auth\Tests\TestCase;

class SocialLoginTest extends TestCase
{
    /** @test */
    public function it_redirects_to_service_auth_page()
    {
        $this->get('login/github')
            ->assertSee('Redirecting to https://github.com/login/oauth/');
    }
}