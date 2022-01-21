<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBCListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('BCLists', function (Blueprint $table) {
            $table->id();
            $table->integer('starter_id');
            $table->string("service");
            $table->string('place');
            $table->integer('type_id');
            $table->string('caserne')->nullable();
            $table->mediumText('description')->nullable();
            $table->boolean('ended')->default(false);
            $table->bigInteger('discord_msg_id')->nullable()->default(null);
            $table->softDeletes();
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
        Schema::dropIfExists('BCLists');
    }
}
