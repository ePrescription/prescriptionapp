<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientLabtestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('patient_labtest'))
        {
            Schema::create('patient_labtest', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('patient_id')->unsigned();
                $table->integer('hospital_id')->unsigned();
                $table->integer('doctor_id')->unsigned();
                $table->string('unique_id', 255)->nullable();
                $table->date('labtest_date');
                $table->string('created_by',255);
                $table->string('modified_by',255);
                $table->timestamps();

                /*$table->unique(array('patient_id', 'hospital_id', 'doctor_id'));*/
            });

            Schema::table('patient_labtest', function(Blueprint $table)
            {
                $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('hospital_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('patient_labtest');
    }
}
