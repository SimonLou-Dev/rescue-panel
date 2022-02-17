<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('Grades', function (Blueprint $table) {
            $table->id();
            $table->string('service');
            $table->string('name');
            $table->boolean('admin')->default(false);
            $table->boolean('default')->default(false);
            $table->integer('power')->default('0');
            $table->integer('discord_role_id')->nullable();

            //Base perm
            $table->boolean('access')->default(false);
            $table->boolean('having_matricule')->default(false);
            //rapports
            $table->boolean('rapport_create')->default(false);
            $table->boolean('rapport_view')->default(false);
            $table->boolean('rapport_HS')->default(false);
            $table->boolean('rapport_modify')->default(false);

            //test de poudre
            $table->boolean('poudretest_create')->default(false);
            $table->boolean('poudretest_view')->default(false);
            $table->boolean('poudretest_HS')->default(false);

            //dossiers
            $table->boolean('dossier_view')->default(false);
            $table->boolean('dosssier_HS')->default(false);
            $table->boolean('patient_edit')->default(false);

            //BC
            $table->boolean('BC_HS')->default(false);
            $table->boolean('BC_open')->default(false);
            $table->boolean('BC_modify_patient')->default(false);
            $table->boolean('BC_fire_personnel_add')->default(false);
            $table->boolean('BC_close')->default(false);
            $table->boolean('BC_medic_view')->default(false);
            $table->boolean('BC_fire_view')->default(false);
            $table->boolean('BC_edit')->default(false);

            //facture
            $table->boolean('facture_HS')->default(false);
            $table->boolean('facture_create')->default(false);
            $table->boolean('facture_view')->default(false);
            $table->boolean('facture_add')->default(false);
            $table->boolean('facture_export')->default(false);
            $table->boolean('facture_paye')->default(false);
            //Personnel

            //Demandes

            //Service

            //Management

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
        Schema::dropIfExists('Grades');
    }
}
