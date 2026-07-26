<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSemesterTable extends Migration
{
    public function up()
    {
       Schema::create('semester', function (Blueprint $table) {
    $table->id('id_semester');

    $table->unsignedBigInteger('id_tahun_ajaran');

    $table->foreign('id_tahun_ajaran')
          ->references('id_tahun_ajaran')
          ->on('tahun_ajaran')
          ->onDelete('cascade');

    $table->enum('nama_semester', ['ganjil','genap']);
    $table->enum('status', ['aktif','nonaktif']);
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('semester');
    }
}
