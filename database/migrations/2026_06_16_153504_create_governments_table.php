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
        Schema::create('governments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('country');
            $table->enum('government_type', ['Ministry', 'Department', 'Agency', 'Authority','National','Provincial','District','Municipal','Local Authority']);
            $table->year('established_year');
            $table->string('contact_email')->unique();
            $table->string('website');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->text('verification_notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
 
            // Foreign key for verified_by
            $table->foreign('verified_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
 
            // Indexes for performance
            $table->index('is_verified');
            $table->index('country');
            $table->index('government_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('governments');
    }
};
