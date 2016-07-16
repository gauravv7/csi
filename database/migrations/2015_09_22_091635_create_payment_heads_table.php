<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentHeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_heads', function (Blueprint $table) {
            $table->engine = 'InnoDb';

            $table->increments('id')->unsigned();
            $table->integer('service_period_id')->unsigned()->index();
            $table->integer('currency_id')->unsigned();
            $table->integer('service_tax_class_id')->unsigned();
            $table->double('amount', 15, 2);
            $table->timestamps();

            $table->foreign('service_period_id')
                    ->references('id')->on('service_periods')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
            $table->foreign('currency_id')
                    ->references('id')->on('currencies')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
            $table->foreign('service_tax_class_id')
                    ->references('id')->on('service_tax_classes')
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
        Schema::drop('payment_heads');
    }
}
