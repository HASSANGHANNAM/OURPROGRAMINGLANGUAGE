<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->integer('Price')->unsigned();
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('category');
            $table->unsignedBigInteger('made_by_id');
            $table->foreign('made_by_id')->references('id')->on('made_by');
            $table->string('image')->nullable();
            $table->string('marketing_name', 45)->nullable();
            $table->string('scientific_name', 45)->nullable();
            $table->string('arabic_name', 45)->nullable();
            $table->date('exp_date');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
