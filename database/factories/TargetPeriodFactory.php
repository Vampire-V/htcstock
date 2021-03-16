<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\KPI\TargetPeriod;
use Faker\Generator as Faker;

$factory->define(TargetPeriod::class, function (Faker $faker) {
    return [
        'name' => $faker->monthName,
        'year' => date('Y')
    ];
});
