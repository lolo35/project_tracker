<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecurringTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('recurring_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("user_id");
            $table->string('task');
            $table->integer('status');
            $table->string('description')->nullable();
            $table->string('recurring');
            $table->integer('dispatch_id')->nullable();
            $table->string('when')->nullable();
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
        Schema::dropIfExists('recurring_tasks');
    }
}
