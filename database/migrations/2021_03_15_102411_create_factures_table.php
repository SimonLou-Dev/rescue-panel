<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Factures', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id');
            $table->integer('rapport_id')->nullable();
            $table->boolean('payed')->default(false);
            $table->integer('price');
            $table->integer('payement_confirm_id')->nullable();
            $table->bigInteger('discord_msg_id')->nullable()->default(null);
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
        Schema::dropIfExists('Factures');
    }
}
