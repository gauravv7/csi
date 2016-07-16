<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBulkPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_payments', function(Blueprint $table){
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('institution_id')->unsigned();
            $table->bigInteger('narration_id')->unsigned()->nullable()->unique();
            $table->integer('member_count')->unsigned();
            $table->double('calculated_amount', 15, 2);
            $table->string('uploads')->nullable();
            $table->tinyInteger('is_rejected')->default(-1);
            $table->string('rejection_reason')->nullable();
            $table->timestamps();

            $table->foreign('institution_id')
                    ->references('id')->on('institutions')
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
        Schema::drop('bulk_payments');
    }
}
