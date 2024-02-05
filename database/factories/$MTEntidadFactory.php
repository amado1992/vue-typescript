<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MTEntidad;
use Faker\Generator as Faker;

$factory->define(MTEntidad::class, function (Faker $faker) {

    return [
        'nombre' => $faker->word,
        'codigo' => $faker->word,
        'osde_id' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
