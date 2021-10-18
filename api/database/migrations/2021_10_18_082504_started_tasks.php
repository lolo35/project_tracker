<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StartedTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('started_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('dispatch_id');
            $table->integer('user_id');
            $table->integer('task_id');
            $table->string('timeframe');
            $table->integer('minutesSpent');
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
        Schema::dropIfExists('started_tasks');
    }
}
