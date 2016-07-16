<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('owner')->unsigned()->default(1);
            $table->integer('owner_group')->unsigned()->default(1);
            $table->integer('perms')->unsigned()->default(4);
            $table->integer('status')->unsigned()->default(0);
            $table->integer('group_memberships')->unsigned();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('owner')
                ->references('id')->on('admins')
                ->onDelete('CASCADE')
                ->onUpdate('CASCADE');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('admins');
    }
}
