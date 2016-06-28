<?php

use Illuminate\Database\Seeder;

class BookingTableSeeder extends Seeder
{

  public function run()
  {
    $faker = Faker\Factory::create();
    $faker->seed(mt_rand());

    $statuses = ['PENDING', 'ACCEPT', 'REJECT'];

    foreach (range(1, 1000) as $index) {
      $booking = App\Models\Booking::create([
        'journey_id' => mt_rand(1, 500),
        'passenger_id' => mt_rand(1, 500),
        'status' => $statuses[mt_rand(0, 2)],
        'remarks' => $faker->sentence,
      ]);
    }
  }

}
