<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Teams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('teamId');
            $table->string('team');
            $table->string('leader');
            $table->integer("leaderId");
            $table->string('members');
            $table->integer("memberId");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('teams');
    }
}
