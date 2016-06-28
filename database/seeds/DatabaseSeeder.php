<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    public static $vehicle_ids = [];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UserTableSeeder::class);
        $this->call(VehicleTableSeeder::class);
        $this->call(JourneyTableSeeder::class);
        $this->call(BookingTableSeeder::class);

        Model::reguard();
    }
}
