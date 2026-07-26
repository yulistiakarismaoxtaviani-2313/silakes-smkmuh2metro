<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuruTable extends Migration
{
    public function up()
    {
        Schema::create('guru', function (Blueprint $table) {
    $table->id('id_guru');

    $table->unsignedBigInteger('id_user');
    $table->foreign('id_user')
          ->references('id_user')
          ->on('users')
          ->cascadeOnDelete();

    $table->string('nip')->unique();
    $table->enum('jenis_kelamin', ['L','P']);
    $table->enum('status', ['aktif','nonaktif']);
    $table->timestamps();
});
    }

    public function down()
    {
        Schema::dropIfExists('guru');
    }
}
