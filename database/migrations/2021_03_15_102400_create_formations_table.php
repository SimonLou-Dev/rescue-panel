<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Formations', function (Blueprint $table) {
            $table->id();
            $table->integer('creator_id');
            $table->integer('try')->default(0);
            $table->integer('success')->default(0);
            $table->integer('average_note')->nullable();
            $table->integer('max_note');
            $table->boolean('question_timed')->default(false);
            $table->boolean('timed')->default(false);
            $table->timestamp('timer')->nullable();
            $table->string('name');
            $table->mediumText('desc');
            $table->string('image')->nullable();
            $table->boolean('public')->default(false);
            $table->boolean('unic_try')->default(false);
            $table->boolean('correction')->default(false);
            $table->boolean('save_on_deco')->default(false);
            $table->boolean('getcertif')->default(false);
            $table->boolean('can_retry_later')->default(false);
            $table->string('time_btw_try')->nullable();
            $table->string('max_try')->default(1);
            $table->integer('certif_id')->nullable();
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
        Schema::dropIfExists('formations');
    }
}
