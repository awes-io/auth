<?php

namespace AwesIO\Auth\Tests\Feature;

use AwesIO\Auth\Facades\Auth;
use AwesIO\Auth\Tests\TestCase;

class LoginTest extends TestCase
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
    }

    /** @test */
    public function it_returns_login_view()
    {
        $this->get('login')
            ->assertViewIs('awesio-auth::auth.login');
    }

    /** @test */
    public function email_is_required()
    {
        $this->json('POST', 'login')
            ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function password_is_required()
    {
        $this->json('POST', 'login')
            ->assertJsonValidationErrors(['password']);
    }
}