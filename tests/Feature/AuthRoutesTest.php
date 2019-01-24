<?php

namespace AwesIO\Auth\Tests\Feature;

use AwesIO\Auth\Tests\TestCase;
use AwesIO\Auth\Tests\Stubs\User;

class AuthRoutesTest extends TestCase
{
    /** @test */
    public function it_has_get_login_route()
    {
        $response = $this->get('login');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_has_post_login_route()
    {
        $response = $this->post('login');

        $response->assertStatus(302);
    }

    /** @test */
    public function it_has_logout_route()
    {
        $response = $this->post('logout');

        $response->assertStatus(302);
    }

    /** @test */
    public function it_has_get_registration_route()
    {
        $response = $this->get('register');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_has_post_registration_route()
    {
        $response = $this->post('register');

        $response->assertStatus(302);
    }

    /** @test */
    public function it_has_login_social_route()
    {
        $response = $this->get('login/service');

        $response->assertStatus(302);
    }

    /** @test */
    public function it_has_login_social_callback_route()
    {
        $response = $this->get('login/service/callback');

        $response->assertStatus(302);
    }

    /** @test */
    public function it_has_two_factor_index_route()
    {
        $response = $this->get('twofactor');

        $response->assertStatus(302);
    }

    /** @test */
    public function it_has_two_factor_store_route()
    {
        $response = $this->post('twofactor');

        $response->assertStatus(302);
    }

    /** @test */
    public function it_has_two_factor_verify_route()
    {
        $response = $this->post('twofactor/verify');

        $response->assertStatus(302);
    }

    /** @test */
    public function it_has_two_factor_delete_route()
    {
        $response = $this->delete('twofactor');

        $response->assertStatus(302);
    }

    /** @test */
    public function it_has_login_two_factor_index_route()
    {
        $response = $this->get('login/twofactor/verify');

        $response->assertStatus(200);
    }
}