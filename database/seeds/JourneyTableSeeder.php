<?php

use Illuminate\Database\Seeder;

class JourneyTableSeeder extends Seeder
{

  public function run()
  {
    $faker = Faker\Factory::create();
    $faker->seed(mt_rand());

    foreach (DatabaseSeeder::$vehicle_ids as $k => $vehicle) {
      $journey = App\Models\Journey::create([
        'vehicle' => $vehicle,
        'startLat' => $faker->latitude,
        'startLng' => $faker->longitude,
        'endLat' => $faker->latitude,
        'endLng' => $faker->longitude,
        'start'   => $faker->address,
        'end'   => $faker->address,
        'cost'   => mt_rand(100, 10000) / 100,
        'remarks'  => $faker->sentence,
        'departureDatetime' => $faker->dateTimeBetween('now', '+10 days'),
        'arrivalDatetime' => $faker->dateTimeBetween('+10 days', '+20 days'),
      ]);
    }
  }

}
