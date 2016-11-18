<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('labtest'))
        {
            Schema::create('labtest', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('test_name', 255);
                $table->tinyInteger('test_status');
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
        Schema::drop('labtest');
    }
}
