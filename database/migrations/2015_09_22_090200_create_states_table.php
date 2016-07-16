<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id')->unsigned();
            $table->string('country_code', 5)->index();
            $table->string('state_code', 5)->unique();    //ISO 3166-2 Codes
            $table->string('name', 150);
            $table->string('capital', 150);
            $table->timestamps();
            
            $table->foreign('country_code')
                    ->references('alpha3_code')->on('countries')
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
        Schema::drop('states');
    }
}
