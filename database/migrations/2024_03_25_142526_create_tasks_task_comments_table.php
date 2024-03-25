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
        Schema::create('tasks_task_comments', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('task_id')->index('fk_tasks_task_comment_id')->comment('refers to tasks_task ID table field');
            $table->integer('comment_author_id')->index('fk_tasks_comment_author_id')->comment('refers to users user_id table field');
            $table->text('comment_text');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks_task_comments');
    }
};
