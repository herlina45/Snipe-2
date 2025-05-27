<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
       {
           Schema::table('ticket_counters', function (Blueprint $table) {
               $table->softDeletes(); // Tambah kolom deleted_at
           });
       }

       public function down()
       {
           Schema::table('ticket_counters', function (Blueprint $table) {
               $table->dropSoftDeletes(); // Hapus kolom deleted_at kalau rollback
           });
       }
};
