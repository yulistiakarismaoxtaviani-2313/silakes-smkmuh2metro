<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapelJurusanTable extends Migration
{
    public function up()
    {
        Schema::create('mapel_jurusan', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('id_mapel');
    $table->foreign('id_mapel')
          ->references('id_mapel')
          ->on('mapel')
          ->cascadeOnDelete();

    $table->unsignedBigInteger('id_jurusan');
    $table->foreign('id_jurusan')
          ->references('id_jurusan')
          ->on('jurusan')
          ->cascadeOnDelete();

    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('mapel_jurusan');
    }
}