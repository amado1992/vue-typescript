<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Traza;
use Faker\Generator as Faker;

$factory->define(Traza::class, function (Faker $faker) {

    return [
        'ip' => $faker->word,
        'accion_id' => $faker->randomDigitNotNull,
        'usuario_id' => $faker->randomDigitNotNull,
        'modelo' => $faker->word,
        'descripcion' => $faker->text,
        'created_at' => $faker->date('Y-m-d H:i:s')
    ];
});
