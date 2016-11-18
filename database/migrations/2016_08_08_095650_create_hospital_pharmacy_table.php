<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalPharmacyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('hospital_pharmacy'))
        {
            Schema::create('hospital_pharmacy', function (Blueprint $table) {
                $table->increments('id')->unsigled();
                $table->integer('hospital_id')->unsigned();
                $table->integer('pharmacy_id')->unsigned();
                $table->string('created_by', 255);
                $table->string('updated_by', 255);
                $table->timestamps();

                $table->unique(array('hospital_id', 'pharmacy_id'));
            });

            Schema::table('hospital_pharmacy', function(Blueprint $table)
            {
                $table->foreign('hospital_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('pharmacy_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('hospital_pharmacy');
    }
}
