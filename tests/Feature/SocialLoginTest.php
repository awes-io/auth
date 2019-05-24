<?php

namespace AwesIO\Auth\Tests\Feature;

use AwesIO\Auth\Tests\TestCase;
use AwesIO\Auth\Tests\Stubs\User;
use AwesIO\Auth\Repositories\Contracts\UserRepository;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use AwesIO\Auth\Services\Contracts\SocialProvidersManager;

class SocialLoginTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    
    /** @test */
    public function it_redirects_to_service_auth_page()
    {
        $this->get('login/github')
            ->assertSee('Redirecting to https://github.com/login/oauth/');
    }

    /** @test */
    public function it_creates_new_user_on_callback()
    {
        $name = uniqid();

        $email = uniqid();

        $serviceUserMock = $this->mock('serviceUser', function ($mock) use ($name, $email) {
            $mock->shouldReceive('getName')
                ->once()
                ->andReturn($name);
            $mock->shouldReceive('getEmail')
                ->twice()
                ->andReturn($email);
            $mock->shouldReceive('getId')
                ->twice()
                ->andReturn(uniqid());
        });

        $serviceMock = $this->mock('service', function ($mock) use ($serviceUserMock) {
            $mock->shouldReceive('user')
                ->andReturn($serviceUserMock);
        });

        $this->mock(SocialProvidersManager::class, function ($mock) use ($serviceMock) {
            $mock->shouldReceive('buildProvider')
                ->once()
                ->with('github')
                ->andReturn($serviceMock);
        });

        $this->get('login/github/callback');

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
        ]);
    }

    /** @test */
    public function it_creates_user_social_on_callback()
    {
        $socialId = uniqid();

        $serviceUserMock = $this->mock('serviceUser', function ($mock) use ($socialId) {
            $mock->shouldReceive('getName')
                ->once()
                ->andReturn(uniqid());
            $mock->shouldReceive('getEmail')
                ->twice()
                ->andReturn(uniqid());
            $mock->shouldReceive('getId')
                ->twice()
                ->andReturn($socialId);
        });

        $serviceMock = $this->mock('service', function ($mock) use ($serviceUserMock) {
            $mock->shouldReceive('user')
                ->andReturn($serviceUserMock);
        });

        $this->mock(SocialProvidersManager::class, function ($mock) use ($serviceMock) {
            $mock->shouldReceive('buildProvider')
                ->once()
                ->with('github')
                ->andReturn($serviceMock);
        });

        $this->get('login/github/callback');

        $this->assertDatabaseHas('users_social', [
            'service' => 'github',
            'social_id' => $socialId,
        ]);
    }

    /** @test */
    public function it_authenticates_user_on_callback()
    {
        $serviceUserMock = $this->mock('serviceUser', function ($mock) {
            $mock->shouldReceive('getName')
                ->once()
                ->andReturn(uniqid());
            $mock->shouldReceive('getEmail')
                ->twice()
                ->andReturn(uniqid());
            $mock->shouldReceive('getId')
                ->twice()
                ->andReturn(uniqid());
        });

        $serviceMock = $this->mock('service', function ($mock) use ($serviceUserMock) {
            $mock->shouldReceive('user')
                ->andReturn($serviceUserMock);
        });

        $this->mock(SocialProvidersManager::class, function ($mock) use ($serviceMock) {
            $mock->shouldReceive('buildProvider')
                ->once()
                ->with('github')
                ->andReturn($serviceMock);
        });

        $this->get('login/github/callback');

        $this->assertAuthenticated();
    }
}