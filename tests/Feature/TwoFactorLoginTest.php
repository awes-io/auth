<?php

namespace AwesIO\Auth\Tests\Feature;

use AwesIO\Auth\Tests\TestCase;
use AwesIO\Auth\Tests\Stubs\User;

class TwoFactorLoginTest extends TestCase
{
    /** @test */
    public function it_returns_view_on_index()
    {
        $this->get('login/twofactor/verify')
            ->assertViewIs('awesio-auth::twofactor.verify');
    }

    /** @test */
    public function it_verifies_two_factor_auth()
    {
        $user = factory(User::class)->create();

        $this->withSession(['two_factor' => (object)[
            'user_id' => $user->id,
            'remember' => true
        ]])->post('login/twofactor/verify', [
            'token' => uniqid()
        ])->assertStatus(302);
    }
}