<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicle_brand_id')->index('vehicles_vehicle_brand_id_foreign');    
            $table->string('name');
            $table->string('year');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['vehicle_brand_id', 'name', 'year']);
            $table->foreign('vehicle_brand_id')->references('id')->on('vehicle_brands')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_models');
    }
}
