<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('alloted_id')->default(0);
            $table->integer('membership_id')->unsigned();
            $table->bigInteger('csi_chapter_id')->unsigned()->index();
            $table->string('email', 254)->unique();
            $table->string('email_extra', 254)->unique()->nullable();
            $table->string('password', 256);
            $table->rememberToken();
            $table->tinyInteger('is_verified')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('membership_id')
                    ->references('id')->on('memberships')
                    ->onDelete('CASCADE')
                    ->onUpdate('CASCADE');
            $table->foreign('csi_chapter_id')
                    ->references('id')->on('csi_chapters')
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
        Schema::drop('members');
    }
}
