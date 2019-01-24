<?php

namespace AwesIO\Auth\Tests;

use AwesIO\Auth\Facades\Auth;
use AwesIO\Auth\AuthServiceProvider;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
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

    protected function assignRouteActionMiddlewares(array $actions, array $middlwares)
    {
        foreach ($actions as $action) {
            app('router')->getRoutes()->getByAction($action)
                ->middleware($middlwares);
        }
    }

    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('two_factor', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('identifier')->nullable();
            $table->string('phone');
            $table->string('dial_code');
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });
    }
}