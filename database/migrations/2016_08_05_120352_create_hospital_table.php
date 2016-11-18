<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('hospital'))
        {
            Schema::create('hospital', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('hospital_id')->unsigned();
                $table->string('hospital_name');
                $table->text('address');
                $table->integer('city')->unsigned();
                $table->integer('country')->unsigned();
                $table->string('hid', 255);
                $table->string('pin', 10);
                $table->string('telephone', 255);
                $table->string('email', 255);
                $table->string('hospital_logo')->nullable();
                $table->string('hospital_photo')->nullable();
                $table->string('website', 2000)->nullable();
                $table->string('created_by', 255);
                $table->string('updated_by', 255);
                $table->timestamps();
            });

            Schema::table('hospital', function(Blueprint $table)
            {
                $table->foreign('hospital_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('hospital');
    }
}
