<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('lab'))
        {
            Schema::create('lab', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('lab_id')->unsigned();
                $table->string('name', 255);
                $table->text('address');
                $table->integer('city')->unsigned();
                $table->integer('country')->unsigned();
                $table->string('pincode', 255);
                $table->string('lid', 255);
                $table->string('telephone', 255);
                $table->string('email', 255)->nullable();
                $table->string('lab_photo')->nullable();
                $table->string('created_by', 255);
                $table->string('updated_by', 255);
                $table->timestamps();
            });

            Schema::table('lab', function(Blueprint $table)
            {
                $table->foreign('lab_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::drop('lab');
    }
}
