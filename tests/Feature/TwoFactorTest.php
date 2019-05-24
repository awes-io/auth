<?php

namespace AwesIO\Auth\Tests\Feature;

use AwesIO\Auth\Tests\TestCase;
use AwesIO\Auth\Tests\Stubs\User;

class TwoFactorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => 'AwesIO\Auth\Seeds\CountryTableSeeder']);
    }

    /** @test */
    public function it_returns_view_on_index()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->post('twofactor', [
            'phone' => ($code = '+7') . ' ' . ($phone = '999 999-99-99'),
        ]);

        $this->actingAs($user)->get('twofactor')
            ->assertViewIs('awesio-auth::twofactor.index')->assertViewHas('qrCode');
    }

    /** @test */
    public function it_stores_new_user_two_factor_record()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->post('twofactor', [
            'phone' => ($code = '+7') . ' ' . ($phone = '999 999-99-99'),
        ]);
        
        $this->assertDatabaseHas('two_factor', [
            'user_id' => $user->id,
            'phone' => $phone,
            'dial_code' => $code
        ]);
    }

    /** @test */
    public function it_verifies_user_two_factor_record()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->post('twofactor', [
            'phone' => ($code = '+7') . ' ' . ($phone = '999 999-99-99'),
        ]);
        
        $this->assertDatabaseHas('two_factor', [
            'user_id' => $user->id,
            'phone' => $phone,
            'dial_code' => $code,
            'verified' => 0
        ]);

        $this->actingAs($user)->post('twofactor/verify', [
            'token' => uniqid()
        ]);

        $this->assertDatabaseHas('two_factor', [
            'user_id' => $user->id,
            'phone' => $phone,
            'dial_code' => $code,
            'verified' => 1
        ]);
    }

    /** @test */
    public function it_destroys_user_two_factor_record()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->post('twofactor', [
            'phone' => ($code = '+7') . ' ' . ($phone = '999 999-99-99'),
        ]);
        
        $this->assertDatabaseHas('two_factor', [
            'user_id' => $user->id,
            'phone' => $phone,
            'dial_code' => $code,
        ]);

        $this->actingAs($user)->delete('twofactor', [], array('HTTP_X-Requested-With' => 'XMLHttpRequest'));

        $this->assertDatabaseMissing('two_factor', [
            'user_id' => $user->id,
            'phone' => $phone,
            'dial_code' => $code,
        ]);
    }
}