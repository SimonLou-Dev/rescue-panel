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
            $table->boolean('facture_export')->default(false);
            $table->boolean('facture_paye')->default(false);

            //Personnel
            $table->boolean('view_PersonnelList')->default(false);
            $table->boolean('set_crossService')->default(false);
            $table->boolean('set_grade')->default(false);
            $table->boolean('set_pilote')->default(false);
            $table->boolean('view_personnelSheet')->default(false);
            $table->boolean('add_note')->default(false);
            $table->boolean('remove_note')->default(false);
            $table->boolean('add_warn_sanction')->default(false);
            $table->boolean('add_MAP_sanction')->default(false);
            $table->boolean('add_degrade_sanction')->default(false);
            $table->boolean('add_exlude_sanction')->default(false);
            $table->boolean('remove_sanctions')->default(false);
            $table->boolean('modify_material')->default(false);

            //Demandes
            $table->boolean('post_service_req')->default(false);
            $table->boolean('view_service_req')->default(false);
            $table->boolean('viewAll_service_req')->default(false);//New
            $table->boolean('modify_service_req')->default(false);

            $table->boolean('post_prime_req')->default(false);
            $table->boolean('view_prime_req')->default(false);
            $table->boolean('viewAll_prime_req')->default(false);//New
            $table->boolean('modify_prime_req')->default(false);

            $table->boolean('post_absences_req')->default(false);//replace to ABS
            $table->boolean('view_absences_req')->default(false);//replace to ABS
            $table->boolean('viewAll_absences_req')->default(false);//replace to ABS
            $table->boolean('modify_absences_req')->default(false);//replace to ABS

            //Service
            $table->boolean('view_rappportHoraire')->default(false);
            $table->boolean('set_service')->default(false);
            $table->boolean('set_other_service')->default(false);

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
