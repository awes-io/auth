<?php

namespace AwesIO\Auth\Seeds;

use Illuminate\Database\Seeder;
use AwesIO\Auth\Models\Country;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            [
                'name' => 'Germany',
                'code' => 'DE',
                'dial_code' => '49',
            ],
            [
                'name' => 'Russia',
                'code' => 'RU',
                'dial_code' => '7',
            ],
            [
                'name' => 'Ukraine',
                'code' => 'UA',
                'dial_code' => '380',
            ],
        ];

        Country::insert($countries);
    }
}
