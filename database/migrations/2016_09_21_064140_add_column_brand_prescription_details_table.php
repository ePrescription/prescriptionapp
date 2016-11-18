<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBrandPrescriptionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('prescription_details', 'brand_id'))
        {
            Schema::table('prescription_details', function (Blueprint $table) {
                $table->integer('brand_id')->unsigned();
            });
        }

        Schema::table('prescription_details', function(Blueprint $table)
        {
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
            //$table->foreign('labtest_id')->references('id')->on('labtest')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prescription_details', function (Blueprint $table) {
            //
        });
    }
}
