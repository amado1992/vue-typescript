<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MTPropuestaLider;
use Faker\Generator as Faker;

$factory->define(MTPropuestaLider::class, function (Faker $faker) {

    return [
        'nombre' => $faker->word,
        'apellido' => $faker->word,
        'cargo' => $faker->word,
        'sector_id' => $faker->randomDigitNotNull,
        'provincia_id' => $faker->randomDigitNotNull,
        'osde_id' => $faker->randomDigitNotNull,
        'instalacion_id' => $faker->randomDigitNotNull,
        'argumentacion' => $faker->text,
        'estado' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
