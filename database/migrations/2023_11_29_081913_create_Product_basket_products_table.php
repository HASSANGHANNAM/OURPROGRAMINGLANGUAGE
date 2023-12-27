<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Product_basket_products', function (Blueprint $table) {
            $table->id();
            $table->integer('Quantity')->unsigned();
            $table->unsignedBigInteger('Product_basket_id');
            $table->foreign('Product_basket_id')->references('id')->on('Product_basket');
            $table->unsignedBigInteger('Product_id');
            $table->foreign('Product_id')->references('id')->on('products');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('Product_basket_products');
    }
};
