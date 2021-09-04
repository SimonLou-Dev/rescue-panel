<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SoftDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('BCTypes', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('Blessures', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('CouleurVetements', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('Hospitals', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('interventions', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('LieuxSurvols', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('ObjRemboursements', function (Blueprint $table) {
            $table->softDeletes();
        });
        Schema::table('ServiceStates', function (Blueprint $table) {
            $table->softDeletes();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
