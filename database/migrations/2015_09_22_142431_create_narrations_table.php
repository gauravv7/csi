<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNarrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('narrations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('payer_id')->unsigned();
            $table->integer('mode')->unsigned();
            $table->string('transaction_number', 30);
            $table->string('bank', 30);
            $table->string('branch', 30);
            $table->date('date_of_payment');
            $table->double('drafted_amount', 15, 2);
            $table->string('proof', 30);
            $table->timestamps();
            
            $table->foreign('mode')
                    ->references('id')->on('payment_modes')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
                    
            $table->foreign('payer_id')
                    ->references('id')->on('members')
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
        Schema::drop('narrations');
    }
}
