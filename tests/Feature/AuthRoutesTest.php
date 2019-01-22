<?php

namespace AwesIO\Auth\Tests\Feature;

use AwesIO\Auth\Facades\Auth;
use AwesIO\Auth\Tests\TestCase;

class AuthRoutesTest extends TestCase
{
    protected $prefix = 'AwesIO\Auth\Controllers\\';

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();

        // $this->loadLaravelMigrations(['--database' => 'testing']);

        // $this->artisan('migrate', ['--database' => 'testing'])->run();

        Auth::routes();
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
        app('router')->getRoutes()
            ->getByAction($this->prefix . 'LoginController@showLoginForm')
            ->middleware('web');

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
        app('router')->getRoutes()
            ->getByAction($this->prefix . 'LoginController@logout')
            ->middleware('web');

        $response = $this->post('logout');

        $response->assertStatus(302);
    }

    /** @test */
    public function it_has_get_registration_route()
    {
        app('router')->getRoutes()
            ->getByAction($this->prefix . 'RegisterController@showRegistrationForm')
            ->middleware('web');

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