<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliesReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplies_receives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplies_id')->comment('物资id');
            $table->foreign('supplies_id')->references('id')->on('supplies')->onDelete('cascade');
            $table->unsignedInteger('stock')->comment('物品库存');
            $table->unsignedInteger('add_stock')->comment('到货数量');
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
        Schema::dropIfExists('supplies_receives');
    }
}
