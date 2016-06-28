<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJourneysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journeys', function (Blueprint $table) {
            $table->string('vehicle');
            $table->timestamp('createdAt');
            $table->double('startLat');
            $table->double('startLng');
            $table->double('endLat');
            $table->double('endLng');
            $table->string('start');
            $table->string('end');
            $table->decimal('cost',10);
            $table->text('remarks');
            $table->dateTime('departureDatetime');
            $table->dateTime('arrivalDatetime');
            $table->primary(['vehicle', 'createdAt']);
            $table->foreign('vehicle')->references('plateNo')->on('vehicles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('journeys');
    }
}
