<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('员工id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('upload_list_id')->comment('上传列表id');
            $table->foreign('upload_list_id')->references('id')->on('upload_lists')->onDelete('cascade');
            $table->unsignedInteger('project_id')->comment('项目id');
            $table->unsignedInteger('device_id')->comment('设备仪器id');
            $table->string('content')->default('')->comment('报告内容');
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
        Schema::dropIfExists('reports');
    }
}
