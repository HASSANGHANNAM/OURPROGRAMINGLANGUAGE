<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('phamacist_id');
            $table->foreign('phamacist_id')->references('id')->on('phatmacist');
            $table->unsignedBigInteger('products_id');
            $table->foreign('products_id')->references('id')->on('products');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorates');
    }
};
