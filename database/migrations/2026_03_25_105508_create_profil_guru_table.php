<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilGuruTable extends Migration
{
    public function up()
    {
        Schema::create('profil_guru', function (Blueprint $table) {
    $table->id('id_profil_guru');

    $table->unsignedBigInteger('id_guru');
    $table->foreign('id_guru')
          ->references('id_guru')
          ->on('guru')
          ->cascadeOnDelete();

    $table->string('no_hp')->nullable();
    $table->string('status_akun')->nullable();
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('profil_guru');
    }
}
