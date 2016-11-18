<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('users'))
        {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->rememberToken()->nullable();
                $table->string('confirmation_code')->nullable();
                $table->tinyInteger('confirmed')->nullable();
                $table->tinyInteger('delete_status');
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
        Schema::drop('users');
    }
}
