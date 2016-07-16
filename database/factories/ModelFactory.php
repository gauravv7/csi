<?php

use App\Member;

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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->defineAs(App\Member::Class, 'institution', function(Faker\Generator $faker){
	return [
				'membership_id' => 1,
				'csi_chapter_id' => $faker->randomElement(range(1, 73)),
				'email' => $faker->email,
				'email_extra' => $faker->email,
				'password' => bcrypt('1234'),
			];
});

$factory->defineAs(App\Member::Class, 'admin-institution', function(Faker\Generator $faker){
	return [
				'membership_id' => 1,
				'csi_chapter_id' => $faker->randomElement(range(1, 73)),
				'email' => 'csi@csi.com',
				'email_extra' => 'csi2@csi.com',
				'password' => bcrypt('KHG@1234IRM'),
			];
});

$factory->defineAs(App\Member::Class, 'individual', function(Faker\Generator $faker){
	return [
				'membership_id' => 2,
				'csi_chapter_id' => $faker->randomElement(range(1, 73)),
				'email' => $faker->email,
				'email_extra' => $faker->email,
				'password' => bcrypt('1234'),
			];
});
