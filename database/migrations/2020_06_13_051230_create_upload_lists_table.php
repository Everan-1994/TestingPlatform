<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('员工id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('ss_name', 50)->default('')->comment('送样人员名称');
            $table->string('sample_num', 64)->index()->default('')->comment('样本编号');
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
        Schema::dropIfExists('upload_lists');
    }
}
