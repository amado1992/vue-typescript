<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Persona;
use Faker\Generator as Faker;

$factory->define(Persona::class, function (Faker $faker) {

    return [
        'nombre_completo' => $faker->word,
        'correo' => $faker->word,
        'telefono' => $faker->word,
        'activo' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
