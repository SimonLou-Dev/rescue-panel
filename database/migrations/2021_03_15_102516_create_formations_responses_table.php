<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormationsResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('FormationsResponses', function (Blueprint $table) {
            $table->id();
            $table->integer('formation_id');
            $table->integer('user_id');
            $table->boolean('finished')->default(false);
            $table->integer('lastquestion_id')->nullable();
            $table->integer('note')->nullable();
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
        Schema::dropIfExists('formations_responses');
    }
}
