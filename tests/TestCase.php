<?php

namespace AwesIO\Auth\Tests;

use AwesIO\Auth\Facades\Auth;
use AwesIO\Auth\Tests\Stubs\User;
use AwesIO\Auth\AuthServiceProvider;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        
        Auth::routes();

        $this->loadLaravelMigrations(['--database' => 'testing']);

        $this->assignRouteActionMiddlewares();

        $this->withFactories(__DIR__ . '/../database/factories');
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

        $app->register( \Laravel\Socialite\SocialiteServiceProvider::class);
    }

    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            AuthServiceProvider::class
        ];
    }

    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'AwesAuth' => Auth::class,
        ];
    }

    protected function assignRouteActionMiddlewares()
    {
        $actions = [
            'AwesIO\Auth\Controllers\LoginController@showLoginForm',
            'AwesIO\Auth\Controllers\LoginController@login',
            'AwesIO\Auth\Controllers\LoginController@logout',
            'AwesIO\Auth\Controllers\RegisterController@showRegistrationForm',
            'AwesIO\Auth\Controllers\TwoFactorLoginController@index',
            'AwesIO\Auth\Controllers\SocialLoginController@redirect',
            'AwesIO\Auth\Controllers\TwoFactorController@index',
            'AwesIO\Auth\Controllers\ForgotPasswordController@showLinkRequestForm',
            'AwesIO\Auth\Controllers\ResetPasswordController@showResetForm',
            'AwesIO\Auth\Controllers\VerificationController@show',
        ];

        $middlwares = ['web'];

        foreach ($actions as $action) {
            app('router')->getRoutes()->getByAction($action)
                ->middleware($middlwares);
        }
    }

    protected function setUpDatabase($app)
    {
        $builder = $app['db']->connection()->getSchemaBuilder();

        $builder->create('two_factor', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('identifier')->nullable();
            $table->string('phone');
            $table->string('dial_code');
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });

        $builder->create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code', 2);
            $table->string('dial_code');
        });
    }
}