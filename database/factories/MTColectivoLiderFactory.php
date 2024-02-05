<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MTColectivoLider;
use Faker\Generator as Faker;

$factory->define(MTColectivoLider::class, function (Faker $faker) {

    return [
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
