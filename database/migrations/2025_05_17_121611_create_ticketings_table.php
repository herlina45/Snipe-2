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
            $table->string('ticket_number')->unique();

            $table->unsignedInteger('requested_by');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedInteger('department_id');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');

            $table->unsignedInteger('request_for')->nullable();
            $table->foreign('request_for')->references('id')->on('users')->onDelete('set null');

            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');

            $table->dateTime('requested_date');
            $table->dateTime('required_date')->nullable();
            $table->text('notes')->nullable();
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
