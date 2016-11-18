<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabLabtestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('lab_labtest'))
        {
            Schema::create('lab_labtest', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('lab_id')->unsigned();
                $table->integer('labtest_id')->unsigned();
                $table->string('created_by', 255);
                $table->string('updated_by', 255);
                $table->timestamps();
            });

            Schema::table('lab_labtest', function(Blueprint $table)
            {
                $table->foreign('lab_id')->references('id')->on('lab')->onDelete('cascade');
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
        Schema::drop('lab_labtest');
    }
}
