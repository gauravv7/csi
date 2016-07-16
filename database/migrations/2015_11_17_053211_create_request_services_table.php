<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_services', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id')->unsigned();
            $table->integer('service_id')->unsigned();
            $table->bigInteger('member_id')->unsigned();
            $table->bigInteger('payment_id')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('service_id')
                    ->references('id')->on('services')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
            $table->foreign('member_id')
                    ->references('id')->on('members')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
            $table->foreign('payment_id')
                    ->references('id')->on('payments')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');

            $table->unique(['service_id', 'member_id']);
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('requests');
    }
}
