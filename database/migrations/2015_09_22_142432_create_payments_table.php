<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('paid_for')->unsigned()->index();
            $table->integer('payment_head_id')->unsigned();
            $table->date('date_of_effect')->nullable;
            $table->integer('service_id')->unsigned();
            $table->dateTime('timestamp_of_effect')->nullable();
            $table->timestamps();

            $table->foreign('paid_for')
                    ->references('id')->on('members')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
            $table->foreign('payment_head_id')
                    ->references('id')->on('payment_heads')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
            $table->foreign('service_id')
                    ->references('id')->on('services')
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
        Schema::drop('payments');
    }
}
