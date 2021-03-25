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
            $table->string('token')->nullable();
            $table->boolean('service')->default(false);
            $table->integer('bc_id')->nullable()->default(null);
            $table->string('liveplace')->nullable();
            $table->integer('tel')->nullable();
            $table->boolean('pilote')->default(false);
            $table->integer('compte')->nullable();
            $table->string('timezone')->nullable();
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
