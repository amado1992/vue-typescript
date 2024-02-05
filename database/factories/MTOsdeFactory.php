<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MTOsde;
use Faker\Generator as Faker;

$factory->define(MTOsde::class, function (Faker $faker) {

    return [
        'nombre' => $faker->word,
        'codigo' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
