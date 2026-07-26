<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengumumanTable extends Migration
{
    public function up()
    {
        Schema::create('pengumuman', function (Blueprint $table) {
    $table->id('id_pengumuman');

    $table->string('judul');
    $table->string('kategori')->nullable();
    $table->text('isi');
    $table->date('tanggal_dibuat')->nullable();
    $table->date('tanggal_tayang')->nullable();

    $table->enum('target', ['semua','kelas','guru','siswa']);

    $table->unsignedBigInteger('id_kelas')->nullable();
    $table->foreign('id_kelas')
          ->references('id_kelas')
          ->on('kelas')
          ->nullOnDelete();

    $table->string('file_lampiran')->nullable();
    $table->enum('status', ['aktif','nonaktif']);

    $table->unsignedBigInteger('id_tahun_ajaran');
    $table->foreign('id_tahun_ajaran')
          ->references('id_tahun_ajaran')
          ->on('tahun_ajaran')
          ->cascadeOnDelete();

    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('pengumuman');
    }
}