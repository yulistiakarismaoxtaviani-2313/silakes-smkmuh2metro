<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilSiswaTable extends Migration
{
    public function up()
    {
       Schema::create('profil_siswa', function (Blueprint $table) {
    $table->id('id_profil_siswa');

    $table->unsignedBigInteger('id_siswa');
    $table->foreign('id_siswa')
          ->references('id_siswa')
          ->on('siswa')
          ->cascadeOnDelete();

    $table->string('tempat_lahir');
    $table->date('tanggal_lahir');
    $table->text('alamat_siswa');
    $table->string('agama');
    $table->string('no_hp')->nullable();
    $table->string('nama_ayah')->nullable();
    $table->string('pekerjaan_ayah')->nullable();
    $table->string('nama_ibu')->nullable();
    $table->string('pekerjaan_ibu')->nullable();
    $table->text('alamat_orang_tua')->nullable();
    $table->string('status_akun')->nullable();
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('profil_siswa');
    }
}
