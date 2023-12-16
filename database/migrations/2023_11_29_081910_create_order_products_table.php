<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('order');
            $table->integer('number_of_produts')->unsigned();
            $table->unsignedBigInteger('Product_id');
            $table->foreign('Product_id')->references('id')->on('products');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
