<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeekServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('WeekServices', function (Blueprint $table) {
            $table->id();
            $table->integer('week_number');
            $table->integer('user_id');
            $table->string('dimanche')->default('00:00:00');
            $table->string('lundi')->default('00:00:00');
            $table->string('mardi')->default('00:00:00');
            $table->string('mercredi')->default('00:00:00');
            $table->string('jeudi')->default('00:00:00');
            $table->string('vendredi')->default('00:00:00');
            $table->string('samedi')->default('00:00:00');
            $table->string('total')->default('00:00:00');
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
        Schema::dropIfExists('week_services');
    }
}
