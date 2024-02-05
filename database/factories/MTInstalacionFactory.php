<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MTInstalacion;
use Faker\Generator as Faker;

$factory->define(MTInstalacion::class, function (Faker $faker) {

    return [
        'nombre' => $faker->word,
        'codigo' => $faker->word,
        'entidad_id' => $faker->randomDigitNotNull,
        'municipio_id' => $faker->randomDigitNotNull,
        'tipoInst_id' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
