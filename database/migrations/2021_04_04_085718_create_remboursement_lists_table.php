<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemboursementListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('RemboursementLists', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->boolean('accepted')->nullable()->default(null);
            $table->integer('admin_id')->nullable()->default(null);
            $table->integer('montant');
            $table->integer('week_number');
            $table->string('reason');
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
        Schema::dropIfExists('RemboursementLists');
    }
}
