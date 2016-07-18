<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfessionalMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('professional_members', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigInteger('id')->unsigned()->index();
            $table->bigInteger('associating_institution_id')->unsigned()->index()->nullable();
            $table->string('organisation', 50);
            $table->string('designation', 30);
            $table->tinyInteger('is_nominee')->default(-2);
            $table->date('nominee_effective')->nullable();
            $table->string('proof_id', 30);
            $table->tinyInteger('is_verified')->default(-1);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('id')
                    ->references('id')->on('individuals')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');

            $table->foreign('associating_institution_id')
                    ->references('id')->on('institutions')
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
        Schema::drop('professional_members');
    }
}
