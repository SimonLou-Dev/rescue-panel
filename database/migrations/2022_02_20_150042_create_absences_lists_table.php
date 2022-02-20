<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absences_lists', function (Blueprint $table) {
            $table->id();
            $table->string('reason');
            $table->date('start_at');
            $table->date('end_at');
            $table->string('user_id');
            $table->string('admin_id')->nullable()->default(null);
            $table->boolean('accepted')->nullable()->default(null);
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
        Schema::dropIfExists('absences_lists');
    }
};
