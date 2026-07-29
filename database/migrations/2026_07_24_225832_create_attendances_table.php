<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('application_id');

            $table->string('centre');

            $table->date('attendance_date');

            $table->enum('status', ['Present', 'Absent', 'Late', 'Excused']);

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->foreign('application_id')->references('APL_ID')->on('applications')->cascadeOnDelete();
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
