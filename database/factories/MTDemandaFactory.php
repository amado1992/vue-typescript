<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MTDemanda;
use Faker\Generator as Faker;

$factory->define(MTDemanda::class, function (Faker $faker) {

    return [
        'instalacion_id' => $faker->randomDigitNotNull,
        'familia_id' => $faker->randomDigitNotNull,
        'producto_id' => $faker->randomDigitNotNull,
        'unidad_medida' => $faker->word,
        'mes_id' => $faker->randomDigitNotNull,
        'anno' => $faker->word,
        'total_demanda_anual' => $faker->randomDigitNotNull,
        'aprobado' => $faker->randomDigitNotNull,
        'deficit' => $faker->randomDigitNotNull,
        'porciento_aprobado' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
