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
        Schema::table('karyawan', function (Blueprint $table) {
            $table->date('tmt_pns_p3k')->nullable()->change();
            $table->date('tmt_gol')->nullable()->change();
            $table->date('tahun_purna_tugas')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->string('tmt_pns_p3k')->nullable()->change();
            $table->string('tmt_gol')->nullable()->change();
            $table->string('tahun_purna_tugas', 10)->nullable()->change();
        });
    }
};
