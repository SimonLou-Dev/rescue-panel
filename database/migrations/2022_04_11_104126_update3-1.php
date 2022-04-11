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
        Schema::table('Rapports', function (Blueprint $table){
           $table->boolean('dispensaire')->default(false);
        });

        Schema::table('Grades', function (Blueprint $table){
            $table->boolean('dispensaire')->default(false);
            $table->boolean('ARSON')->default(false);
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
};
