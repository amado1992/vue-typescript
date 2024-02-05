<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Familia;
use Faker\Generator as Faker;

$factory->define(Familia::class, function (Faker $faker) {

    return [
        'nombre_familia' => $faker->word,
        'descripcion' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
