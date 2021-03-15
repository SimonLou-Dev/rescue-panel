<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormationsQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('FormationsQuestions', function (Blueprint $table) {
            $table->id();
            $table->integer('formation_id');
            $table->string('type');
            $table->string('correction')->nullable();
            $table->string('max_note');
            $table->json('responses');
            $table->json('right_response');
            $table->string('name');
            $table->string('img')->nullable();
            $table->mediumText('desc');
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
        Schema::dropIfExists('formations_questions');
    }
}
