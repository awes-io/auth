<?php

namespace AwesIO\Auth\Tests\Unit\Services;

use AwesIO\Auth\Tests\TestCase;
use AwesIO\Auth\Tests\Stubs\User;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Two\User as SocialUser;
use AwesIO\Auth\Repositories\EloquentUserRepository;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class UserRepositoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    
    /** @test */
    public function it_returns_user_by_email()
    {
        $user = factory(User::class)->create();

        $mock = $this->mock(SocialUser::class, function ($mock) use ($user) {
            $mock->shouldReceive('getEmail')
                ->once()
                ->andReturn($user->email);
            $mock->shouldReceive('getId')
                ->once()
                ->andReturn(uniqid());
        });

        $repository = new EloquentUserRepository();

        $this->assertInstanceOf(User::class, $repository->getUserBySocial($mock, 'github'));
    }
    
    /** @test */
    public function it_returns_user_by_social_id()
    {
        $user = factory(User::class)->create();

        DB::table('users_social')->insert([
            'user_id' => $user->id,
            'service' => $sevice = 'github',
            'social_id' => $socialId = uniqid()
        ]);

        $mock = $this->mock(SocialUser::class, function ($mock) use ($socialId) {
            $mock->shouldReceive('getEmail')
                ->once()
                ->andReturn(uniqid());
            $mock->shouldReceive('getId')
                ->once()
                ->andReturn($socialId);
        });

        $repository = new EloquentUserRepository();

        $this->assertInstanceOf(User::class, $repository->getUserBySocial($mock, $sevice));
    }
}