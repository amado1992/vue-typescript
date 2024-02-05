<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Usuario;
use Faker\Generator as Faker;

$factory->define(Usuario::class, function (Faker $faker) {

    return [
        'uuid' => $faker->word,
        'nombre' => $faker->word,
        'apellidos' => $faker->word,
        'email' => $faker->word,
        'admin' => $faker->word,
        'activo' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
