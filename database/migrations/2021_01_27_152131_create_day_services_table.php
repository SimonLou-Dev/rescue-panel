<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDayServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('day_services', function (Blueprint $table) {
            $table->id();
            $table->integer('week');
            $table->time('dimanche')->default("00:00:00");
            $table->time('lundi')->default("00:00:00");
            $table->time('mardi')->default("00:00:00");
            $table->time('mercredi')->default("00:00:00");
            $table->time('jeudi')->default("00:00:00");
            $table->time('vendredi')->default("00:00:00");
            $table->time('samedi')->default("00:00:00");
            $table->time('total')->default("00:00:00");
            $table->integer('user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('day_services');
    }
}
