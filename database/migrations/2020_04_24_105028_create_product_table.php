<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->string('p_id')->primary();
            $table->string('p_name');
            $table->double('p_price', 8, 2);
            $table->unsignedBigInteger('unit_type_id')->nullable();
            $table->unsignedBigInteger('p_type_id')->nullable();
            $table->timestamps();
            $table->foreign('unit_type_id')->references('unit_type_id')->on('unit_type');
            $table->foreign('p_type_id')->references('p_type_id')->on('product_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
}
