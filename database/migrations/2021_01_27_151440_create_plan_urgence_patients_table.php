<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanUrgencePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_urgence_patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_name');
            $table->integer('PU_ID');
            $table->integer('rapport_id');
            $table->timestamp('addat');
            $table->string('vetements');
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
        Schema::dropIfExists('plan_urgence_patients');
    }
}
