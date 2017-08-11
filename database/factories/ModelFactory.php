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
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'profile_picture' => 'images/default-profile-image.jpg',
        'role' => 0,
        'location' => 0,
        'active' => 1,
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Admin::class, function(Faker\Generator $faker) {

    return [
        'user_id' => App\User::all()->random()->id,
        'role' => $faker->numberBetween($min = 0, $max = 2),
    ];
});

$factory->define(App\Document::class, function (Faker\Generator $faker) {
    static $password;

    $ext = array('.doc', '.docx', '.jpg', '.jpeg', '.png', '.pdf');

    return [
        'user_id' => App\User::all()->random()->id,
        'filename' => $faker->randomNumber($nbDigits = NULL).".doc",
        'filepath' => '/storage/app/'.$faker->randomNumber($nbDigits = NULL).".doc",
        'deleted' => 0,
        'description' => $faker->sentence,
        'public' => $faker->numberBetween($min = 0, $max = 1),
        'shared_by_admin' => $faker->numberBetween($min = 0, $max = 1),
        'tag_id' => 0,
    ];
});

// $factory->define(App\Reminder::class, function(Faker\Generator $faker) {
//     return [
//         'user_id' => App\User::all()->random()->id,
//         'due_date' => $faker->unique()->dateTimeBetween('+1 days', '+1 week')->format('Y-m-d'),
//         'title' => $faker->sentence($nb = 3, $asText = false),
//         'description' => $faker->sentence($nb = 8, $asText = false),
//         'updated_by' => 1,
//         'municipality' => $faker->numberBetween($min = 0, $max = 1),
//         'active' => 0,
//         'deleted' => 0
//     ];
// });

// $factory->define(App\ComplianceUser::class, function(Faker\Generator $faker) {

//     return [
//         'user_id' => App\User::all()->random()->id,
//         'date_complied' => date('Y-m-d'),
//         'reminder_id' => App\Reminder::all()->random()->id,
//         'score' => 0,
//         'approved_by' => 0
//     ];
// });

// $factory->define(App\ComplianceMunicipality::class, function(Faker\Generator $faker) {

//     return [
//         'location' => $faker->numberBetween($min = 0, $max = 10),
//         'date_complied' => date('Y-m-d'),
//         'reminder_id' => App\Reminder::all()->random()->id,
//         'score' => $faker->randomNumber($nbDigits = NULL),        
//         'approved_by' => 0
//     ];
// });
