<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('made_by', function (Blueprint $table) {
            $table->id();
            $table->string('made_by_name', 45)->unique()->nullable();
            $table->string('made_by_Arabic_name', 45)->unique()->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('made_by');
    }
};
