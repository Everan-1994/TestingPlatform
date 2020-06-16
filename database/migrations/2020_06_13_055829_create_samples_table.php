<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSamplesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->string('sample_num', 64)->index()->default('')->comment('样本编号');
            $table->string('sample_name', 64)->default('')->comment('样本名称');
            $table->string('unit_name', 50)->default('')->comment('单位名称');
            $table->string('site_name', 50)->default('')->comment('站点名称');
            $table->dateTime('receive_at')->comment('取样日期');
            $table->string('weather', 20)->default('')->comment('天气');
            $table->string('qrcode')->default('')->comment('二维码');
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
        Schema::dropIfExists('samples');
    }
}
