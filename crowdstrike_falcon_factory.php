<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Crowdstrike_falcon_model::class, function (Faker\Generator $faker) {

    return [
        'agent_id' => str_replace("-", "", $faker->uuid()),
        'customer_id' => strtoupper($faker->uuid()),
        'sensor_operational' => $faker->randomElement(['true', 'false']),
        'sensor_version' => $faker->randomElement(['6.52.16501.0', '7.01.17208.0', '7.02.17302.0', '7.03.17401.0']), 
        'fulldiskaccess_granted' => $faker->randomElement(['Yes', 'No']),
        'tamper_protection' => $faker->randomElement(['Enabled', 'Disabled']),
        'overall_zta_score' => $faker->numberBetween(0, 100),
        'os_zta_score' => $faker->numberBetween(0, 100),
        'sensor_zta_score' => $faker->numberBetween(0, 100),
    ];
});