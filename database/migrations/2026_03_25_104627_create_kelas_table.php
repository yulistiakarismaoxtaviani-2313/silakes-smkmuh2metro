<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKelasTable extends Migration
{
    public function up()
    {
        Schema::create('kelas', function (Blueprint $table) {
    $table->id('id_kelas');

    $table->string('nama_kelas');

    $table->unsignedBigInteger('id_jurusan');
    $table->foreign('id_jurusan')
          ->references('id_jurusan')
          ->on('jurusan')
          ->cascadeOnDelete();

    $table->enum('tingkat', ['X','XI','XII']);

    $table->unsignedBigInteger('id_tahun_ajaran');
    $table->foreign('id_tahun_ajaran')
          ->references('id_tahun_ajaran')
          ->on('tahun_ajaran');

    $table->unsignedBigInteger('id_semester');
    $table->foreign('id_semester')
          ->references('id_semester')
          ->on('semester');

    $table->enum('status', ['aktif','nonaktif']);
    $table->timestamps();
});

    }

    public function down()
    {
        Schema::dropIfExists('kelas');
    }
}
