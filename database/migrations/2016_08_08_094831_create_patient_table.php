<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('patient'))
        {
            Schema::create('patient', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('patient_id')->unsigned();
                $table->string('name', 255);
                $table->text('address');
                $table->integer('city')->unsigned();
                $table->integer('country')->unsigned();
                $table->integer('pid_no_prefix')->unsigned()->nullable();
                $table->string('pid', 255)->unique();
                $table->string('telephone', 255);
                $table->string('email', 255)->nullable();
                $table->string('patient_photo')->nullable();
                $table->date('dob')->nullable();
                $table->integer('age')->nullable();
                $table->string('place_of_birth', 255)->nullable();
                $table->string('nationality', 255)->nullable();
                $table->tinyInteger('gender');
                $table->tinyInteger('married')->nullable();
                $table->string('created_by', 255);
                $table->string('updated_by', 255);
                $table->timestamps();
            });

            Schema::table('patient', function(Blueprint $table)
            {
                $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('city')->references('id')->on('cities')->onDelete('cascade');
                $table->foreign('country')->references('id')->on('countries')->onDelete('cascade');
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
        Schema::drop('patient');
    }
}
