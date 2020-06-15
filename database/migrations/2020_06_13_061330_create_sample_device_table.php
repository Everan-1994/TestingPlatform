<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_device', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_id')->comment('样本id');
            $table->foreign('sample_id')->references('id')->on('samples')->onDelete('cascade');
            $table->unsignedBigInteger('device_id')->comment('设备id');
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sample_device');
    }
}
