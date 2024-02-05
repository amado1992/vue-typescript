<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MTMunicipio;
use Faker\Generator as Faker;

$factory->define(MTMunicipio::class, function (Faker $faker) {

    return [
        'nombre' => $faker->word,
        'provincia_id' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
