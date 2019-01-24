<?php

namespace AwesIO\Auth\Tests;

use AwesIO\Auth\Facades\Auth;
use AwesIO\Auth\AuthServiceProvider;

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
}