<?php

namespace AwesIO\Auth\Tests\Feature;

use AwesIO\Auth\Tests\TestCase;
use AwesIO\Auth\Models\TwoFactor;
use AwesIO\Auth\Tests\Stubs\User;

class LoginTest extends TestCase
{
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

    /** @test */
    public function it_responds_with_json_to_ajax_request_after_successful_login()
    {
        $user = factory(User::class)->create();

        $this->json('POST', 'login', [
            'email' => $user->email,
            'password' => 'secret'
        ], ['X-Requested-With' => 'XMLHttpRequest']
        )->assertExactJson([
            'redirectUrl' => 'http://localhost' . config('awesio-auth.redirects.login')
        ]);
    }

    /** @test */
    public function it_handles_two_factor_authentication_and_redirects()
    {
        $user = factory(User::class)->create();

        factory(TwoFactor::class)->create([
            'user_id' => $user->id,
            'verified' => 1
        ]);

        $this->json('POST', 'login', [
            'email' => $user->email,
            'password' => 'secret'
        ])->assertRedirect('/login/twofactor/verify');

        $this->assertGuest();
    }
}