<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TestPoudre extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PouderTests', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('patient_id');
            $table->string('lieux_prelevement');
            $table->boolean('on_skin_positivity')->default(false);
            $table->boolean('on_clothes_positivity')->default(false);
            $table->bigInteger('discord_msg_id')->nullable()->default(null);
            $table->string('service');
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
        Schema::dropIfExists('PouderTests');
    }
}
