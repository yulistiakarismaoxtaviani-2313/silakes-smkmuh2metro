<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaliKelasTable extends Migration
{
    public function up()
    {
        Schema::create('wali_kelas', function (Blueprint $table) {
    $table->id('id_wali_kelas');

    $table->unsignedBigInteger('id_guru');
    $table->foreign('id_guru')
          ->references('id_guru')
          ->on('guru')
          ->cascadeOnDelete();

    $table->unsignedBigInteger('id_kelas');
    $table->foreign('id_kelas')
          ->references('id_kelas')
          ->on('kelas')
          ->cascadeOnDelete();

    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('wali_kelas');
    }
}
