<?php

use Illuminate\Database\Seeder;

class VehicleTableSeeder extends Seeder
{

  public function run()
  {
    $faker = Faker\Factory::create();
    $faker->seed(mt_rand());

    foreach (range(1, 500) as $index) {
      $vehicle = App\Models\Vehicle::create([
        'plateNo' => 'S' . chr(mt_rand(65, 90)) . chr(mt_rand(65, 90)) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9) . mt_rand(1, 9) . chr(mt_rand(65, 90)),
        'numSeat' => mt_rand(1, 4),
        'brand'   => $faker->company,
        'model'   => $faker->firstName,
        'color'   => $faker->colorName,
        'driver'  => mt_rand(1, 500),
      ]);

      DatabaseSeeder::$vehicle_ids[] = $vehicle['plateNo'];
    }

  }

}
