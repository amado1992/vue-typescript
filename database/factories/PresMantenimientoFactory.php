<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MTPresMantenimiento;
use Faker\Generator as Faker;

$factory->define(MTPresMantenimiento::class, function (Faker $faker) {

    return [
        'descMtto' => $faker->word,
        'unidadMedida' => $faker->word,
        'prespMttoT1' => $faker->word,
        'prespMttoT2' => $faker->word,
        'prespMttoTotal' => $faker->word,
        'prespMttoC1' => $faker->word,
        'prespMttoC2' => $faker->word,
        'prespMttoContrat' => $faker->word,
        'realMttoT1' => $faker->word,
        'realMttoT2' => $faker->word,
        'realMttoTotal' => $faker->word,
        'realMttoC1' => $faker->word,
        'realMttoC2' => $faker->word,
        'realMttoContrat' => $faker->word,
        'porCientoMttoTot1' => $faker->word,
        'porCientoMttoTot2' => $faker->word,
        'porCientoMttoTot3' => $faker->word,
        'porCientoMttoContrat1' => $faker->word,
        'porCientoMttoContrat2' => $faker->word,
        'porCientoMttoContrat3' => $faker->word,
        'mes' => $faker->randomDigitNotNull,
        'anno' => $faker->randomDigitNotNull,
        'entidad' => $faker->randomDigitNotNull,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
