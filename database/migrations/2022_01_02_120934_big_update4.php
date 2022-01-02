<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BigUpdate4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Users', function (Blueprint $table) {
            $table->integer('grade_id')->nullable()->default(null)->change();
            $table->string('name')->nullable()->default(null)->change();
            $table->dropColumn('password');
        });

        Schema::table('BCLists', function (Blueprint $table) {
            $table->integer('discord_msg_id')->nullable()->default(null);
        });

        Schema::table('Factures', function (Blueprint $table) {
            $table->integer('discord_msg_id')->nullable()->default(null);
            $table->dropColumn('msg_discord_id');
        });

        Schema::table('PouderTests', function (Blueprint $table) {
            $table->integer('discord_msg_id')->nullable()->default(null);
        });

        Schema::table('Primes', function (Blueprint $table) {
            $table->integer('discord_msg_id')->nullable()->default(null);
        });

        Schema::table('Rapports', function (Blueprint $table) {
            $table->integer('discord_msg_id')->nullable()->default(null);
            $table->dropColumn('msg_discord_id');
        });

        Schema::table('remboursement_lists', function (Blueprint $table) {
            $table->integer('discord_msg_id')->nullable()->default(null);
        });

        Schema::table('Services', function (Blueprint $table) {
            $table->integer('discord_msg_id')->nullable()->default(null);
        });

        Schema::table('Vols', function (Blueprint $table) {
            $table->integer('discord_msg_id')->nullable()->default(null);
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
