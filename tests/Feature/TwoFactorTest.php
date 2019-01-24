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

        $this->actingAs($user)->get('twofactor')
            ->assertViewIs('awesio-auth::twofactor.index');
    }

    /** @test */
    // public function it_stores_new_user_two_factor_record()
    // {
    //     $user = factory(User::class)->create();

    //     dd($this->actingAs($user)->post('twofactor', [
    //         'phone' => uniqid(),
    //         'dial_code' => '7'
    //     ]));
    // }
}