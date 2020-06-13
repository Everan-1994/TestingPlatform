<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSampleProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sample_project', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sample_id')->comment('样本id');
            $table->foreign('sample_id')->references('id')->on('samples')->onDelete('cascade');
            $table->unsignedBigInteger('project_id')->comment('项目id');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
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
        Schema::dropIfExists('sample_project');
    }
}
