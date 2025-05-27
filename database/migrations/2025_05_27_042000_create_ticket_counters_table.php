<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketCountersTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_counters', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('department_id');
            $table->string('department_code', 10);
            $table->string('month_year', 4); // Misal '0525' buat Mei 2025
            $table->unsignedInteger('counter')->default(0);
            $table->timestamps();

            // Index buat performa
            $table->unique(['department_id', 'month_year']);
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_counters');
    }
}
?>
