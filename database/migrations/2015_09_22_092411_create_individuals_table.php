<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIndividualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individuals', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('member_id')->unsigned()->index();
            $table->integer('membership_type_id')->unsigned()->index();
            $table->integer('salutation_id')->unsigned()->index();
            $table->string('first_name', 30);
            $table->string('middle_name', 30)->nullable();
            $table->string('last_name', 30)->nullable();
            $table->string('card_name', 30);
            $table->string('gender', 1);
            $table->date('dob');
            $table->string('profile_photograph', 30)->default('sample.png');
            $table->string('signature', 30)->default('sample.png');
            $table->timestamps();
            

            $table->foreign('member_id')
                    ->references('id')->on('members')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
            $table->foreign('membership_type_id')
                    ->references('id')->on('membership_types')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
            $table->foreign('salutation_id')
                    ->references('id')->on('salutations')
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
        Schema::drop('individuals');
    }
}
