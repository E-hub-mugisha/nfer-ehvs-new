<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employment_records', function (Blueprint $table) {

            $table->id();

            $table->foreignId('employee_id')
                ->constrained('employees')
                ->onDelete('cascade');

            $table->foreignId('employer_id')
                ->constrained('employers')
                ->onDelete('cascade');

            $table->string('job_title');

            $table->string('department')->nullable();

            $table->date('start_date');

            $table->date('end_date')->nullable();

            $table->enum('employment_status', [
                'active',
                'terminated',
                'resigned',
                'contract-ended'
            ])->default('active');

            $table->string('exit_reason')->nullable();

            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employment_records');
    }
};