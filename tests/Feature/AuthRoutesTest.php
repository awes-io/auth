<?php

namespace AwesIO\Auth\Tests\Feature;

use AwesIO\Auth\Facades\Auth;
use AwesIO\Auth\Tests\TestCase;

class AuthRoutesTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        Auth::routes();

        $this->assignRouteActionMiddlewares([
            'AwesIO\Auth\Controllers\LoginController@showLoginForm',
            'AwesIO\Auth\Controllers\LoginController@logout',
            'AwesIO\Auth\Controllers\RegisterController@showRegistrationForm'
        ], ['web']);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', env('APP_DEBUG', true));

        // $app->register( \Laravel\Socialite\SocialiteServiceProvider::class);
    }

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
}