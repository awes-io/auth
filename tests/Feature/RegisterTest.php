<?php

namespace AwesIO\Auth\Tests\Feature;

use Mockery;
use AwesIO\Auth\Facades\Auth;
use AwesIO\Auth\Tests\TestCase;
use AwesIO\Auth\Tests\Stubs\User;

class RegisterTest extends TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        Auth::routes();

        $this->loadLaravelMigrations(['--database' => 'testing']);

        $this->withFactories(__DIR__ . '/../../database/factories');

        $this->assignRouteActionMiddlewares([
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
    }

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