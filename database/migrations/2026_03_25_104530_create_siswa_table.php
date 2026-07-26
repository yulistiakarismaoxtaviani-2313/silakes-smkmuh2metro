<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiswaTable extends Migration
{
    public function up()
    {
        Schema::create('siswa', function (Blueprint $table) {
    $table->id('id_siswa');

    $table->unsignedBigInteger('id_user');
    $table->foreign('id_user')
          ->references('id_user')
          ->on('users')
          ->cascadeOnDelete();

    $table->string('nis')->unique();
    $table->enum('jenis_kelamin', ['L','P']);
    $table->enum('status', ['aktif','nonaktif']);
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('siswa');
    }
}
