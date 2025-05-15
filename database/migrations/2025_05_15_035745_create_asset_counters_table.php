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
        Schema::create('asset_counters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('department_code');
            $table->unsignedBigInteger('category_id');
            $table->string('sub_category_code'); // tetap pakai model_number
            $table->string('month_year'); // MMYY
            $table->integer('counter')->default(0);
            $table->timestamps();

            $table->unique([
                'company_id',
                'department_code',
                'category_id',
                'sub_category_code',
                'month_year'
            ], 'unique_counter_per_combo');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_counters');
    }
};
