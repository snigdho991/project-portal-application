<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

// models...
use Modules\Ums\Entities\UserSocialAccount;

$factory->define(UserSocialAccount::class, function (Faker $faker) {
    return [
        'username' => $faker->userName,
		'social_site_id' => $faker->numberBetween(1, 4),
		'user_id' => $faker->word,
    ];
});
