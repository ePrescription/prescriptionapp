<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalPatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('hospital_patient'))
        {
            Schema::create('hospital_patient', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('hospital_id')->unsigned();
                $table->integer('patient_id')->unsigned();
                $table->string('created_by', 255);
                $table->string('updated_by', 255);
                $table->timestamps();

                $table->unique(array('hospital_id', 'patient_id'));
            });

            Schema::table('hospital_patient', function(Blueprint $table)
            {
                $table->foreign('hospital_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('hospital_patient');
    }
}
