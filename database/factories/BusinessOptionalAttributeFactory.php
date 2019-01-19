<?php

use Faker\Generator as Faker;

$factory->define(App\Models\BusinessOptionalAttribute::class, function (Faker $faker) use ($factory) {
    return [
        'business_id' => $factory->create(App\Models\Business::class)->id,
        'optional_attribute_id' => $factory->create(App\Models\OptionalAttribute::class)->id,
        'description' => $faker->emoji
    ];
});
