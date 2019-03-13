<?php

namespace AwesIO\Auth\Tests\Feature;

use AwesIO\Auth\Tests\TestCase;
use AwesIO\Auth\Tests\Stubs\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

class VerificationTest extends TestCase
{
    /** @test */
    public function it_returns_code_verification_view()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null
        ]);

        $this->actingAs($user)->get('email/verify')
            ->assertViewIs('awesio-auth::auth.verify');
    }

    /** @test */
    public function it_verifies_email_using_code()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verified_at' => null
        ]);

        $code = $user->generateVerificationCode();

        $this->actingAs($user)->withSession(
            ['email_verification' => 
                [
                    'code' => $code, 'expire' => now()->addMinutes(60)
                ]
            ]
        )->post('email/verify', ['code' => $code]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email_verified_at' => null
        ]);
    }

    /** @test */
    public function it_fails_email_verification_if_code_not_stored()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null
        ]);

        $code = $user->generateVerificationCode();

        $this->actingAs($user)->post('email/verify', ['code' => $code])
            ->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verified_at' => null
        ]);
    }

    /** @test */
    public function it_fails_email_verification_if_code_is_not_valid()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null
        ]);

        $code = $user->generateVerificationCode();

        $this->actingAs($user)->withSession(
            ['email_verification' => 
                [
                    'code' => $user->generateVerificationCode(), 
                    'expire' => now()->addMinutes(60)
                ]
            ]
        )->post('email/verify', ['code' => $code])->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verified_at' => null
        ]);
    }

    /** @test */
    public function it_fails_email_verification_if_code_is_expired()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null
        ]);

        $code = $user->generateVerificationCode();

        $this->actingAs($user)->withSession(
            ['email_verification' => 
                [
                    'code' => $code, 'expire' => now()->subMinute()
                ]
            ]
        )->post('email/verify', ['code' => $code])->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verified_at' => null
        ]);
    }

    /** @test */
    public function it_verifies_email_using_link()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verified_at' => null
        ]);

        $url = URL::temporarySignedRoute(
            'verification.verify', 
            $expire = now()->addMinutes(60), 
            ['id' => $user->getKey()]
        );

        $this->actingAs($user)->get($url);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email_verified_at' => null
        ]);
    }

    /** @test */
    public function it_fails_email_verification_if_link_is_not_valid()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null
        ]);

        $url = URL::temporarySignedRoute(
            'verification.verify', 
            $expire = now()->addMinutes(60), 
            ['id' => $user->getKey()]
        );

        $this->actingAs($user)->get($url . 1)->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verified_at' => null
        ]);
    }

    /** @test */
    public function it_fails_email_verification_using_link_if_id_is_invalid()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null
        ]);

        $url = URL::temporarySignedRoute(
            'verification.verify', 
            $expire = now()->addMinutes(60), 
            ['id' => $user->getKey() + 1]
        );

        $this->actingAs($user)->get($url)->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email_verified_at' => null
        ]);
    }

    /** @test */
    public function it_resends_verification_email()
    {
        $user = factory(User::class)->create([
            'email_verified_at' => null
        ]);

        Notification::fake();

        $this->actingAs($user)->get('email/resend');

        Notification::assertSentTo($user, VerifyEmail::class);
    }
}