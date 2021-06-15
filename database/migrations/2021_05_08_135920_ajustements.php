<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Ajustements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('WeekServices', function (Blueprint $table) {
            $table->string('ajustement')->default('00:00:00');
        });

        Schema::table('Users', function (Blueprint $table) {
           $table->integer('serviceState')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('WeekServices', function (Blueprint $table) {
            //
        });
    }
}
