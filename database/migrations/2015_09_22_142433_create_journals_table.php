<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJournalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('journals', function (Blueprint $table) {
            $table->engine = "InnoDB";

            $table->bigInteger('payment_id')->unsigned();
            $table->bigInteger('narration_id')->unsigned();
            $table->tinyInteger('is_rejected')->default(-1);
            $table->string('rejection_reason', 1024)->nullable();
            $table->double('paid_amount', 15, 2);
            $table->timestamps();

            $table->primary(['payment_id', 'narration_id']);
            $table->foreign('payment_id')
                    ->references('id')->on('payments')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
            $table->foreign('narration_id')
                    ->references('id')->on('narrations')
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
        Schema::drop('journals');
    }
}
