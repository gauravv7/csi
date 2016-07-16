<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCsiStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csi_states', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('csi_region_id')->unsigned()->index();
            $table->string('state_code', 5)->index();
            $table->timestamps();
            
            $table->foreign('csi_region_id')
                    ->references('id')->on('csi_regions')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
            $table->foreign('state_code')
                    ->references('state_code')->on('states')
                    ->onDelete('NO ACTION')
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
        Schema::drop('csi_states');
    }
}
