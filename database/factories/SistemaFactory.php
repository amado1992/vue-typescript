<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\MTSistema;
use Faker\Generator as Faker;

$factory->define(MTSistema::class, function (Faker $faker) {
    return [
        'nombre' => $faker->text(50),
        'descripcion' => $faker->text(300)
    ];
});
