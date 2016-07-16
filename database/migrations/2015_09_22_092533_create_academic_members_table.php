<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcademicMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('academic_members', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigInteger('id')->unsigned()->index();
            $table->integer('institution_type_id')->unsigned()->index();
            $table->tinyInteger('is_student_branch')->default(-2);
            $table->string('rejection_reason', 1024)->nullable();
            $table->timestamps();

            $table->primary('id');
            $table->foreign('id')
                    ->references('id')->on('institutions')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
            $table->foreign('institution_type_id')
                    ->references('id')->on('institution_types')
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
        Schema::drop('academic_members');
    }
}
