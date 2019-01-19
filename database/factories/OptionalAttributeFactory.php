<?php

use Faker\Generator as Faker;

$factory->define(App\Models\OptionalAttribute::class, function (Faker $faker) use ($factory) {
    return [
        'uuid' => $faker->uuid,
        'name' => $faker->text(20),
        'image' => $faker->image('/tmp/',400,300, null, false),
    ];
});
