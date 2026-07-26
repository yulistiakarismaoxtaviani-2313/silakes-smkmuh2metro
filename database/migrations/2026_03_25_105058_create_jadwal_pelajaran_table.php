<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJadwalPelajaranTable extends Migration
{
    public function up()
    {
        Schema::create('jadwal_pelajaran', function (Blueprint $table) {
    $table->id('id_jadwal_pelajaran');

    $table->unsignedBigInteger('id_kelas');
    $table->foreign('id_kelas')
          ->references('id_kelas')
          ->on('kelas')
          ->cascadeOnDelete();

    $table->unsignedBigInteger('id_mapel');
    $table->foreign('id_mapel')
          ->references('id_mapel')
          ->on('mapel');

    $table->unsignedBigInteger('id_guru');
    $table->foreign('id_guru')
          ->references('id_guru')
          ->on('guru');

    $table->unsignedBigInteger('id_tahun_ajaran');
    $table->foreign('id_tahun_ajaran')
          ->references('id_tahun_ajaran')
          ->on('tahun_ajaran');

    $table->unsignedBigInteger('id_semester');
    $table->foreign('id_semester')
          ->references('id_semester')
          ->on('semester');

    $table->enum('hari', ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']);
    $table->time('jam_mulai');
    $table->time('jam_selesai');
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('jadwal_pelajaran');
    }
}