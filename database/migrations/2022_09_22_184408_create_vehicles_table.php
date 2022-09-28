<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_model_id')->index('vehicles_vehicle_model_id_foreign');
            $table->string('plate')->unique();
            $table->string('color');
            $table->date('entry_date');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('vehicle_model_id')->references('id')->on('vehicle_models')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}
