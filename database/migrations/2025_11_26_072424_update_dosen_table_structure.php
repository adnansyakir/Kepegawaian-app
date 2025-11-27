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
        Schema::table('dosen', function (Blueprint $table) {
            // Add nidn column after nip
            $table->string('nidn')->nullable()->after('nip');
            
            // Rename prodi to prodi_id if exists
            if (Schema::hasColumn('dosen', 'prodi')) {
                $table->renameColumn('prodi', 'prodi_id');
            }
            
            // Rename pangkat to pangkat_id if exists
            if (Schema::hasColumn('dosen', 'pangkat')) {
                $table->renameColumn('pangkat', 'pangkat_id');
            }
            
            // Rename golongan to golongan_id if exists
            if (Schema::hasColumn('dosen', 'golongan')) {
                $table->renameColumn('golongan', 'golongan_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dosen', function (Blueprint $table) {
            // Drop nidn column
            $table->dropColumn('nidn');
            
            // Rename back columns
            if (Schema::hasColumn('dosen', 'prodi_id')) {
                $table->renameColumn('prodi_id', 'prodi');
            }
            
            if (Schema::hasColumn('dosen', 'pangkat_id')) {
                $table->renameColumn('pangkat_id', 'pangkat');
            }
            
            if (Schema::hasColumn('dosen', 'golongan_id')) {
                $table->renameColumn('golongan_id', 'golongan');
            }
        });
    }
};
