<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBCPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('BCPatients', function (Blueprint $table) {
            $table->id();
            $table->boolean('idcard')->default(false);
            $table->integer('patient_id');
            $table->integer('rapport_id');
            $table->integer('blessure_type')->nullable();
            $table->integer('couleur')->nullable();
            $table->integer('BC_id');
            $table->string('name');
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
        Schema::dropIfExists('BCPatients');
    }
}
