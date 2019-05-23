<?php

namespace AwesIO\Auth\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use AwesIO\Auth\Tests\TestCase;
use AwesIO\Auth\Models\TwoFactor;
use AwesIO\Auth\Tests\Stubs\User;
use AwesIO\Auth\Services\AuthyTwoFactor;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class AuthyTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    
    /** @test */
    public function it_returns_false_if_something_wrong()
    {

        $user = factory(User::class)->create();

        factory(TwoFactor::class)->create([
            'user_id' => $user->id
        ]);

        $mock = $this->mock(Client::class, function ($mock) use ($user) {
            $mock->shouldReceive('request')
                ->with(
                    'POST', 
                    'https://api.authy.com/protected/json/users/new?api_key=', 
                    ['form_params' => $this->getUserRegistrationPayload($user)]
                )
                ->once()
                ->andThrow(\Exception::class);
        });

        $authy = new AuthyTwoFactor($mock);

        $this->assertFalse($authy->register($user));
    }
    
    /** @test */
    public function it_registers_new_user()
    {

        $user = factory(User::class)->create();

        factory(TwoFactor::class)->create([
            'user_id' => $user->id
        ]);

        $mock = $this->mock(Client::class, function ($mock) use ($user) {
            $mock->shouldReceive('request')
                ->with(
                    'POST', 
                    'https://api.authy.com/protected/json/users/new?api_key=', 
                    ['form_params' => $this->getUserRegistrationPayload($user)]
                )
                ->once()
                ->andReturn(new Response);
        });

        $authy = new AuthyTwoFactor($mock);

        $this->isNull($authy->register($user));
    }
    
    /** @test */
    public function it_verifies_token_as_valid()
    {
        $user = factory(User::class)->create();

        factory(TwoFactor::class)->create([
            'user_id' => $user->id
        ]);

        $token = uniqid();

        $response = $this->mock(Response::class, function ($mock) {
            $mock->shouldReceive('getBody')->once()->andReturn(json_encode(['token' => 'is valid']));
        });

        $mock = $this->mock(Client::class, function ($mock) use ($response, $token, $user) {
            $mock->shouldReceive('request')
                ->with(
                    'GET', 
                    'https://api.authy.com/protected/json/verify/' 
                        . $token . '/' . $user->twoFactor->identifier . '?force=true&api_key='
                )
                ->once()
                ->andReturn($response);
        });

        $authy = new AuthyTwoFactor($mock);

        $this->assertTrue($authy->verifyToken($user, $token));
    }
    
    /** @test */
    public function it_verifies_token_as_invalid()
    {
        $user = factory(User::class)->create();

        factory(TwoFactor::class)->create([
            'user_id' => $user->id
        ]);

        $token = uniqid();

        $response = $this->mock(Response::class, function ($mock) {
            $mock->shouldReceive('getBody')->once()->andReturn(json_encode(['token' => 'is not valid']));
        });

        $mock = $this->mock(Client::class, function ($mock) use ($response, $token, $user) {
            $mock->shouldReceive('request')
                ->with(
                    'GET', 
                    'https://api.authy.com/protected/json/verify/' 
                        . $token . '/' . $user->twoFactor->identifier . '?force=true&api_key='
                )
                ->once()
                ->andReturn($response);
        });

        $authy = new AuthyTwoFactor($mock);

        $this->assertFalse($authy->verifyToken($user, $token));
    }
    
    /** @test */
    public function it_verifies_token_as_invalid_if_smth_wrong()
    {
        $user = factory(User::class)->create();

        factory(TwoFactor::class)->create([
            'user_id' => $user->id
        ]);

        $token = uniqid();

        $mock = $this->mock(Client::class, function ($mock) use ($token, $user) {
            $mock->shouldReceive('request')
                ->with(
                    'GET', 
                    'https://api.authy.com/protected/json/verify/' 
                        . $token . '/' . $user->twoFactor->identifier . '?force=true&api_key='
                )
                ->once()
                ->andThrow(\Exception::class);
        });

        $authy = new AuthyTwoFactor($mock);

        $this->assertFalse($authy->verifyToken($user, $token));
    }
    
    /** @test */
    public function it_removes_user_from_two_factor_service()
    {
        $user = factory(User::class)->create();

        factory(TwoFactor::class)->create([
            'user_id' => $user->id
        ]);

        $mock = $this->mock(Client::class, function ($mock) use ($user) {
            $mock->shouldReceive('request')
                ->with(
                    'POST', 
                    'https://api.authy.com/protected/json/users/delete/'
                        . $user->twoFactor->identifier . '?api_key='
                )
                ->once()
                ->andReturn(null);
        });

        $authy = new AuthyTwoFactor($mock);

        $this->assertTrue($authy->remove($user));
    }
    
    /** @test */
    public function it_doesnt_remove_user_from_two_factor_service()
    {
        $user = factory(User::class)->create();

        factory(TwoFactor::class)->create([
            'user_id' => $user->id
        ]);

        $mock = $this->mock(Client::class, function ($mock) use ($user) {
            $mock->shouldReceive('request')
                ->with(
                    'POST', 
                    'https://api.authy.com/protected/json/users/delete/'
                        . $user->twoFactor->identifier . '?api_key='
                )
                ->once()
                ->andThrow(\Exception::class);
        });

        $authy = new AuthyTwoFactor($mock);

        $this->assertFalse($authy->remove($user));
    }
    
    /** @test */
    public function it_returns_link_to_qr_code()
    {
        $user = factory(User::class)->create();

        factory(TwoFactor::class)->create([
            'user_id' => $user->id
        ]);

        $mock = $this->mock(Client::class, function ($mock) use ($user) {
            $mock->shouldReceive('request')
                ->with(
                    'POST', 
                    'https://api.authy.com/protected/json/users/'
                        . $user->twoFactor->identifier . '/secret?api_key='
                )
                ->once()
                ->andReturn(new Response);
        });

        $authy = new AuthyTwoFactor($mock);

        $this->isNull($authy->qrCode($user));
    }
    
    /** @test */
    public function it_doesnt_return_link_to_qr_code()
    {
        $user = factory(User::class)->create();

        factory(TwoFactor::class)->create([
            'user_id' => $user->id
        ]);

        $mock = $this->mock(Client::class, function ($mock) use ($user) {
            $mock->shouldReceive('request')
                ->with(
                    'POST', 
                    'https://api.authy.com/protected/json/users/'
                        . $user->twoFactor->identifier . '/secret?api_key='
                )
                ->once()
                ->andThrow(\Exception::class);
        });

        $authy = new AuthyTwoFactor($mock);

        $this->assertFalse($authy->qrCode($user));
    }

    /**
     * Get data needed for user registration on Authy
     *
     * @param $user
     * @return array
     */
    protected function getUserRegistrationPayload($user)
    {
        return [
            'user' => [
                'email' => $user->email,
                'cellphone' => $user->twoFactor->phone,
                'country_code' => $user->twoFactor->dial_code
            ]
        ];
    }
}