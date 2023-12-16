<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('Email_address', 255)->unique()->nullable();
            $table->string('Phone_number', 15)->unique()->nullable();
            $table->string('password', 255);
            $table->double('wallet')->nullable();
            $table->tinyInteger('type');
            $table->rememberToken();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
