<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Users', function (Blueprint $table) {
            $table->id();
            $table->integer('grade_id')->default(1);
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('token');
            $table->boolean('service');
            $table->string('liveplace');
            $table->integer('tel');
            $table->boolean('pilote')->default(false);
            $table->integer('compte');
            $table->string('timezone');
            $table->string('bg_img')->nullable();
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
        Schema::dropIfExists('users');
    }
}
