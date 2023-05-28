<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
  public function up()
  {
    Schema::create('reservations', function (Blueprint $table) {
      $table->unsignedBigInteger('id', true);
      $table->unsignedBigInteger('shop_id');
      $table->unsignedBigInteger('user_id');
      $table->integer('num_of_users');
      $table->datetime('start_at');
      $table->timestamps();
      $table->engine = 'InnoDB';

      $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  public function down()
  {
    Schema::dropIfExists('reservations');
  }
}