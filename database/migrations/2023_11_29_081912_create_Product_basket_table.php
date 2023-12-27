<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('Product_basket', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phatmacist_id');
            $table->foreign('phatmacist_id')->references('id')->on('phatmacist');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('Product_basket');
    }
};
