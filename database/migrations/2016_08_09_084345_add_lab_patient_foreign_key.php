<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLabPatientForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(Schema::hasTable('lab'))
        {
            /*Schema::table('lab', function (Blueprint $table) {

                $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            });*/
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::table('lab', function (Blueprint $table) {
            $table->dropForeign('lab_patient_id_foreign');
        });*/
    }
}
