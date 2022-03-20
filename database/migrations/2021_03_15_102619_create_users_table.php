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
            $table->integer('matricule')->nullable()->unique();
            $table->bigInteger('discord_id')->nullable()->unique();
            $table->string('name')->nullable()->default(null);
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->integer('fire_grade_id')->default(1);
            $table->integer('medic_grade_id')->default(1);
            $table->string('token')->nullable();
            $table->boolean('OnService')->default(false);
            $table->integer('bc_id')->nullable()->default(null);
            $table->string('liveplace')->nullable();
            $table->string('tel')->nullable();
            $table->boolean('pilote')->default(false);
            $table->integer('compte')->nullable();
            $table->string('bg_img')->nullable();
            $table->json('sanctions')->nullable();
            $table->json('materiel')->nullable();
            $table->json('note')->nullable();
            $table->json('notification_preference')->nullable();
            $table->bigInteger('last_service_update')->nullable();
            //Mutualisation MDT
            $table->boolean('moderator')->default(false);
            $table->boolean('dev')->default(false);
            $table->boolean('medic')->default(false);
            $table->boolean('fire')->default(false);
            $table->boolean('crossService')->default(false);
            $table->string('service')->nullable();

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
        Schema::dropIfExists('Users');
    }
}
