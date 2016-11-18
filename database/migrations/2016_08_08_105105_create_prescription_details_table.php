<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrescriptionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('prescription_details'))
        {
            Schema::create('prescription_details', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('patient_prescription_id')->unsigned();
                $table->integer('drug_id')->unsigned();
                $table->text('brief_description');
                $table->string('dosage', 255);
                $table->integer('no_of_days');
                $table->tinyInteger('morning');
                $table->tinyInteger('afternoon');
                $table->tinyInteger('night');
                $table->string('created_by',255);
                $table->string('modified_by',255);
                $table->timestamps();
            });

            Schema::table('prescription_details', function(Blueprint $table)
            {
                $table->foreign('patient_prescription_id')->references('id')->on('patient_prescription')->onDelete('cascade');
                $table->foreign('drug_id')->references('id')->on('drugs')->onDelete('cascade');
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
        Schema::drop('prescription_details');
    }
}
