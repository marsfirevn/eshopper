<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Entities\Admin::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'remember_token' => str_random(10),
        'is_verify' => true,
        'is_active' => true,
    ];
});

$factory->define(App\Entities\Customer::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'remember_token' => str_random(10),
        'is_verify' => true,
        'is_active' => true,
    ];
});

$factory->define(App\Entities\Category::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(5),
        'is_active' => true,
    ];
});

$factory->define(App\Entities\Product::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(5),
        'price' => $faker->numberBetween(100000, 500000),
        'description' => $faker->sentence(5),
        'is_active' => true,
    ];
});
