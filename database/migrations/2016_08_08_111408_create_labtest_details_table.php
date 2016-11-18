<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabtestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('labtest_details'))
        {
            Schema::create('labtest_details', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('patient_labtest_id')->unsigned();
                $table->integer('labtest_id')->unsigned();
                //$table->string('labtest_name', 255);
                $table->text('brief_description');
                $table->string('created_by',255);
                $table->string('modified_by',255);
                $table->timestamps();
            });

            Schema::table('labtest_details', function(Blueprint $table)
            {
                $table->foreign('patient_labtest_id')->references('id')->on('patient_labtest')->onDelete('cascade');
                $table->foreign('labtest_id')->references('id')->on('labtest')->onDelete('cascade');
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
        Schema::drop('labtest_details');
    }
}
