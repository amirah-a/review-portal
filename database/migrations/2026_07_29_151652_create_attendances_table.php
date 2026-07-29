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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('application_id')
                ->constrained('applications', 'APL_ID')
                ->cascadeOnDelete();

            $table->string('centre');
            $table->date('attendance_date');

            $table->enum('status', [
                'Present',
                'Absent',
                'Late',
                'Excused',
            ])->default('Present');

            $table->text('remarks')->nullable();

            $table->timestamps();

            // Prevent duplicate attendance records for the same participant on the same day
            $table->unique(['application_id', 'attendance_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};