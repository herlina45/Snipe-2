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
        Schema::create('ticketings', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // Ticketing Number
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade'); // Relasi ke users
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade'); // Relasi ke departments
            $table->foreignId('request_for')->nullable()->constrained('users')->onDelete('set null'); // Relasi ke users (opsional)
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Relasi ke categories
            $table->dateTime('requested_date');
            $table->dateTime('required_date')->nullable(); 
            $table->text('notes')->nullable(); // Notes (teks biasa)
            $table->string('status')->default('Waiting for approval');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticketings');
    }
};
