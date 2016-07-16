<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_members', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigInteger('id')->unsigned()->index();
            $table->bigInteger('student_branch_id')->unsigned()->index();
            $table->string('course_name', 30);
            $table->integer('course_duration')->unsigned();
            $table->string('college_name', 80);
            $table->string('course_branch', 30)->nullable();
            $table->string('proof_id', 30)->default('sample.jpg');
            $table->tinyInteger('is_verified')->default(-1);
            $table->timestamps();

            $table->primary('id');
            $table->foreign('id')
                    ->references('id')->on('individuals')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
            $table->foreign('student_branch_id')
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
        Schema::drop('student_members');
    }
}
