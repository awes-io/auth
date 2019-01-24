<?php

namespace AwesIO\Auth\Tests\Feature;

use AwesIO\Auth\Facades\Auth;
use AwesIO\Auth\Tests\TestCase;
use AwesIO\Auth\Tests\Stubs\User;

class LoginTest extends TestCase
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
            'AwesIO\Auth\Controllers\LoginController@showLoginForm',
            'AwesIO\Auth\Controllers\LoginController@login',
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

        $app['config']->set('auth.providers.users.model', User::class);

        $this->setUpDatabase($app);
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

    /** @test */
    public function it_can_login_user()
    {
        $user = factory(User::class)->create();

        $this->json('POST', 'login', [
            'email' => $user->email,
            'password' => 'secret'
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function it_cant_login_without_valid_password()
    {
        $user = factory(User::class)->create();

        $this->json('POST', 'login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $this->assertGuest();
    }
}