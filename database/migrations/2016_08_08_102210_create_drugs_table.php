<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('drugs'))
        {
            Schema::create('drugs', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('drug_name', 255);
                $table->string('brand_name', 255);
                $table->string('manufacturer', 255)->nullable();
                $table->tinyInteger('drug_status');
                $table->string('created_by',255)->default('admin');
                $table->string('modified_by',255)->default('admin');
                $table->timestamps();
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
        Schema::drop('drugs');
    }
}
