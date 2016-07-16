<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->bigInteger('member_id')->unsigned()->index();
            $table->integer('membership_type_id')->unsigned()->index();
            $table->integer('salutation_id')->unsigned()->index();
            $table->string('name', 80);
            $table->string('head_name', 50);
            $table->string('head_designation', 30);
            $table->string('email', 254);
            $table->string('country_code', 5);
            $table->string('mobile', 10);
            $table->string('profile_photograph', 30)->default('sample.png');
            $table->string('signatures', 30)->default('sample.png');
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
        Schema::drop('institutions');
    }
}
