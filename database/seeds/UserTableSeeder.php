<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{

  public function run()
  {
    $faker = Faker\Factory::create();
    $faker->seed(mt_rand());

    foreach (range(1, 500) as $index) {
      $user = App\Models\User::create([
        'username'    => str_replace('.', '_', $faker->unique()->userName),
        'fullname'    => $faker->name,
        'password'    => bcrypt('123123'),
        'phone'       => $faker->phoneNumber ,
      ]);
    }
  }

}
