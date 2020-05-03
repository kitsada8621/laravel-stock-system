<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_return', function (Blueprint $table) {
            $table->id('p_return_id');
            $table->unsignedBigInteger('p_sale_id');
            $table->integer('p_return_unit')->default(0);
            $table->string('times_out');
            $table->foreign('p_sale_id')->references('p_sale_id')->on('product_sale')->onDelete('cascade');
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
        Schema::dropIfExists('product_return');
    }
}
