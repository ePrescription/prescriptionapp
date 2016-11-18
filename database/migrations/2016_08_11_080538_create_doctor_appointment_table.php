<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('doctor_appointment'))
        {
            Schema::create('doctor_appointment', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('patient_id')->unsigned();
                $table->integer('hospital_id')->unsigned();
                $table->integer('doctor_id')->unsigned();
                $table->text('brief_history')->nullable();
                $table->date('appointment_date');
                $table->time('appointment_time');
                $table->string('created_by',255);
                $table->string('modified_by',255);
                $table->timestamps();

                $table->unique(array('patient_id', 'hospital_id', 'doctor_id'));
            });

            Schema::table('doctor_appointment', function(Blueprint $table)
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
        Schema::drop('doctor_appointment');
    }
}
