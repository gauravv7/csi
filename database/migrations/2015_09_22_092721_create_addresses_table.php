<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id')->unsigned();
            $table->integer('type_id')->unsigned()->index();
            $table->bigInteger('member_id')->unsigned()->index();
            $table->string('country_code', 5)->index()->nullable();
            $table->string('state_code', 5)->index()->nullable();
            $table->string('address_line_1', 100);
            $table->string('city', 80);
            $table->integer('pincode')->unsigned();
            $table->timestamps();

            $table->unique(['type_id', 'member_id']);

            $table->foreign('type_id')
                    ->references('id')->on('address_types')
                    ->onDelete('CASCADE');
            $table->foreign('member_id')
                    ->references('id')->on('members')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
            $table->foreign('state_code')
                    ->references('state_code')->on('states')
                    ->onDelete('SET NULL')
                    ->onUpdate('CASCADE');
            $table->foreign('country_code')
                    ->references('alpha3_code')->on('countries')
                    ->onDelete('SET NULL')
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
        Schema::drop('addresses');
    }
}
