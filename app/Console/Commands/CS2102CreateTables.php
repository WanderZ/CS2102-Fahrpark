<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Vehicle;
use App\Models\Journey;
use App\Models\Transaction;
use App\Models\Booking;
use Illuminate\Console\Command;

class CS2102CreateTables extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'cs2102:create-tables';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Create/initialize tables for this app.';

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $this->comment('Creating tables...');

    User::createTable();
    User::doInsert([
      'fullname' => 'Admin User',
      'username' => 'admin',
      'password' => bcrypt('123456'),
      'isAdmin' => 1,
      'phone' => '12345678',
    ]);

    Vehicle::createTable();
    Journey::createTable();
    Journey::createTriggers();
    Journey::createStoredProcedures();
    Booking::createTable();
    Transaction::createTable();
    Transaction::createProcedures();

    $this->comment('Done!');
  }
}
