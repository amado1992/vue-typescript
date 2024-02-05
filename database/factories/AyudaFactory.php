<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Ayuda;
use Faker\Generator as Faker;

$factory->define(Ayuda::class, function (Faker $faker) {

    return [
        'topico' => $faker->text,
        'subtopico' => $faker->text,
        'consecutivo' => $faker->word,
        'ruta' => $faker->text,
        'usuario_id' => $faker->randomDigitNotNull,
        'modulo' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
