<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRapportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Rapports', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('interType');
            $table->integer('transport');
            $table->longText('description');
            $table->integer('price');
            $table->dateTime('ATA_start')->nullable();
            $table->dateTime('ATA_end')->nullable();
            $table->integer('patient_id');
            $table->integer('msg_discord_id')->nullable();
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
        Schema::dropIfExists('rapports');
    }
}
