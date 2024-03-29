<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Primes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Primes', function (Blueprint $table) {
            $table->id();
            $table->string('reason');
            $table->integer('user_id');
            $table->integer('week_number');
            $table->integer('montant');
            $table->boolean('accepted')->nullable()->default(null);
            $table->integer('admin_id')->nullable()->default(null);
            $table->string('service');
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
        Schema::dropIfExists('Primes');
    }
}
