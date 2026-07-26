<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalUjianTable extends Migration
{
    public function up()
    {
        Schema::create('jadwal_ujian', function (Blueprint $table) {
    $table->id('id_jadwal_ujian');

    $table->unsignedBigInteger('id_kelas');
    $table->foreign('id_kelas')
          ->references('id_kelas')
          ->on('kelas')
          ->cascadeOnDelete();

    $table->unsignedBigInteger('id_tahun_ajaran');
    $table->foreign('id_tahun_ajaran')
          ->references('id_tahun_ajaran')
          ->on('tahun_ajaran');

    $table->unsignedBigInteger('id_semester');
    $table->foreign('id_semester')
          ->references('id_semester')
          ->on('semester');

    $table->unsignedBigInteger('id_jenis_ujian');
    $table->foreign('id_jenis_ujian')
          ->references('id_jenis_ujian')
          ->on('jenis_ujian');

    $table->string('judul');
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_ujian');
    }
}
