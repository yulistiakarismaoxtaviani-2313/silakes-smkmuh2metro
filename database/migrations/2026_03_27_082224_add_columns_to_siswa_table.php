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
    Schema::table('siswa', function (Blueprint $table) {
        // Cek dulu, kalau id_kelas BELUM ada, baru buat
        if (!Schema::hasColumn('siswa', 'id_kelas')) {
            $table->unsignedBigInteger('id_kelas')->nullable()->after('id_user');
        }
        
        // Cek dulu, kalau foto BELUM ada, baru buat
        if (!Schema::hasColumn('siswa', 'foto')) {
            $table->string('foto')->nullable()->after('status');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn(['id_kelas', 'foto']);
        });
    }
};