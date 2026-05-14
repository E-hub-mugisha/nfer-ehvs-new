<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nid')->unique();
            $table->string('first_name');
            $table->string('last_name');

            $table->string('gender')->nullable();
            $table->date('dob')->nullable();

            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->string('photo')->nullable();

            $table->string('district')->nullable();
            $table->string('sector')->nullable();

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
