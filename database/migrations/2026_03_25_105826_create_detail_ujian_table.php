<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailUjianTable extends Migration
{
    public function up()
    {
        Schema::create('detail_ujian', function (Blueprint $table) {
    $table->id('id_detail_ujian');

    $table->unsignedBigInteger('id_jadwal_ujian');
    $table->foreign('id_jadwal_ujian')
          ->references('id_jadwal_ujian')
          ->on('jadwal_ujian')
          ->cascadeOnDelete();

    $table->date('tanggal');
    $table->time('jam_mulai');
    $table->time('jam_selesai');

    $table->unsignedBigInteger('id_mapel');
    $table->foreign('id_mapel')
          ->references('id_mapel')
          ->on('mapel');

    $table->unsignedBigInteger('id_pengawas');
    $table->foreign('id_pengawas')
          ->references('id_guru')
          ->on('guru');

    $table->string('ruangan');
    $table->string('hari');
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('detail_ujian');
    }
}
