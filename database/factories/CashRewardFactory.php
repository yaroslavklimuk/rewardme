<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\CashReward;
use Faker\Generator as Faker;

$factory->define(CashReward::class, function (Faker $faker) {
    return [
        return [
            'user_id' => $faker->randomDigit,
            'amount' => $faker->randomDigit,
            'payed' => false
        ];
    ];
});
