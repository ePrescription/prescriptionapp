<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('doctor'))
        {
            Schema::create('doctor', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('doctor_id')->unsigned();
                $table->string('name', 255);
                $table->text('address');
                $table->integer('city')->unsigned();
                $table->integer('country')->unsigned();
                $table->string('did', 255);
                $table->string('telephone', 255);
                $table->string('email', 255)->nullable();
                $table->string('qualifications', 255)->nullable();
                $table->string('specialty', 255)->nullable();
                $table->integer('experience')->unsigned()->nullable();
                $table->string('doctor_photo')->nullable();
                $table->string('created_by', 255);
                $table->string('updated_by', 255);
                $table->timestamps();
            });

            Schema::table('doctor', function(Blueprint $table)
            {
                $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('doctor');
    }
}
