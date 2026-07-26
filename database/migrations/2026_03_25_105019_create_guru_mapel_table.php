<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuruMapelTable extends Migration
{
    public function up()
    {
       Schema::create('guru_mapel', function (Blueprint $table) {
    $table->id('id_guru_mapel');

    $table->unsignedBigInteger('id_guru');
    $table->foreign('id_guru')
          ->references('id_guru')
          ->on('guru')
          ->cascadeOnDelete();

    $table->unsignedBigInteger('id_mapel');
    $table->foreign('id_mapel')
          ->references('id_mapel')
          ->on('mapel');

    $table->unsignedBigInteger('id_kelas');
    $table->foreign('id_kelas')
          ->references('id_kelas')
          ->on('kelas');

    $table->unsignedBigInteger('id_tahun_ajaran');
    $table->foreign('id_tahun_ajaran')
          ->references('id_tahun_ajaran')
          ->on('tahun_ajaran');

    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('guru_mapel');
    }
}
