<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{

    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->string('name', 255)->notNullable();
            $table->unsignedBigInteger('area_id')->notNullable();
            $table->unsignedBigInteger('genre_id')->notNullable();
            $table->text('description')->notNullable();
            $table->string('image_url', 255)->notNullable();
            $table->timestamps();
            $table->engine = 'InnoDB';

            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('genre_id')->references('id')->on('genres');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
