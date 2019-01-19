<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\BusinessHour::class, function (Faker $faker) use ($factory) {
    return [
        'business_id'       => $factory->create(\App\Models\Business::class)->make(),
        'open_period_mins'  => $faker->numberBetween(400, 600),
        'close_period_mins' => $faker->numberBetween(700, 1200),
        'wd_1' => 1
    ];
});
