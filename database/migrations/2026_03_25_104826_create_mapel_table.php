<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapelTable extends Migration
{
    public function up()
    {
        Schema::create('mapel', function (Blueprint $table) {
            $table->id('id_mapel');
            $table->string('nama_mapel');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mapel');
    }
}
