<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks_task_comments', function (Blueprint $table) {
            $table->foreign(['comment_author_id'], 'fk_tasks_comment_author_id')->references(['id'])->on('users');
            $table->foreign(['task_id'], 'fk_tasks_task_comment_id')->references(['id'])->on('tasks_task');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks_task_comments', function (Blueprint $table) {
            $table->dropForeign('fk_tasks_comment_author_id');
            $table->dropForeign('fk_tasks_task_comment_id');
        });
    }
};
