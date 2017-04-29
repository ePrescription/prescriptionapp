<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeReceiptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('fee_receipt'))
        {
            Schema::create('fee_receipt', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('patient_id')->unsigned();
                $table->integer('hospital_id')->unsigned();
                $table->integer('doctor_id')->unsigned();
                $table->decimal('fee', 7,2)->nullable();
                $table->string('created_by',255);
                $table->string('modified_by',255);
                $table->timestamps();
            });

            Schema::table('fee_receipt', function(Blueprint $table)
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
        Schema::drop('fee_receipt');
    }
}
