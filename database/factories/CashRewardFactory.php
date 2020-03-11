<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(\App\Models\CashReward::class, function (Faker $faker) {
    return [
            'user_id' => $faker->randomDigit,
            'amount' => $faker->randomDigit,
            'payed' => false
    ];
});
