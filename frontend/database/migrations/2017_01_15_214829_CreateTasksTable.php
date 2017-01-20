<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('command');
            $table->string('output_message');
            $table->string('command_directory');
            $table->boolean('run_from_build_directory');
            $table->timestamps();
        });

        $task = new \App\Task;
        $task->name = 'Composer Install';
        $task->command = 'sudo composer install';
        $task->output_message = 'Installing Composer Dependencies';
        $task->command_directory = '';
        $task->run_from_build_directory = true;
        $task->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
