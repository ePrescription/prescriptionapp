<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalLabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('hospital_lab'))
        {
            Schema::create('hospital_lab', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('hospital_id')->unsigned();
                $table->integer('lab_id')->unsigned();
                $table->string('created_by', 255);
                $table->string('updated_by', 255);
                $table->timestamps();

                $table->unique(array('hospital_id', 'lab_id'));
            });

            Schema::table('hospital_lab', function(Blueprint $table)
            {
                $table->foreign('hospital_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('lab_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('hospital_lab');
    }
}
