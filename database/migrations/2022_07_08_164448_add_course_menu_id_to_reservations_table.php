<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourseMenuIdToReservationsTable extends Migration
{
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->unsignedBigInteger('course_menu_id')->nullable()->after('start_at');
            $table->foreign('course_menu_id')->references('id')->on('course_menus')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['course_menu_id']);
            $table->dropColumn('course_menu_id');
        });
    }
}
