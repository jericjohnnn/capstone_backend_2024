<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');;
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('school_id_number')->nullable();
            $table->string('grade_year')->nullable();
            $table->enum('offense_status', ['Unflagged', 'Warned', 'Penalized', 'Banned'])
                ->default('Unflagged');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
