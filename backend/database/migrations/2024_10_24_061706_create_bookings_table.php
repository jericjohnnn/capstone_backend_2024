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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained()->onDelete('cascade');;
            $table->foreignId('student_id')->constrained()->onDelete('cascade');;
            $table->enum('learning_mode', ['Online', 'Face to Face', 'In School']);
            $table->string('subject');
            $table->string('location')->nullable();
            $table->string('online_meeting_platform')->nullable();
            $table->string('contact_number');
            $table->enum('status', ['Pending', 'Ongoing', 'Completed', 'Canceled'])
                ->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
