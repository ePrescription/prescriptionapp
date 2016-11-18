<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('brands'))
        {
            Schema::create('brands', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('brand_name', 255);
                $table->integer('drug_id')->unsigned();
                $table->tinyInteger('brand_status');
                $table->string('created_by',255)->default('admin');
                $table->string('modified_by',255)->default('admin');
                $table->timestamps();
            });
        }

        Schema::table('brands', function(Blueprint $table)
        {
            $table->foreign('drug_id')->references('id')->on('drugs')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('brands');
    }
}
