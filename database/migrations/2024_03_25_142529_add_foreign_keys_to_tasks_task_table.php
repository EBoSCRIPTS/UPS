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
        Schema::table('tasks_task', function (Blueprint $table) {
            $table->foreign(['made_by'], 'fk_task_made_by')->references(['id'])->on('users');
            $table->foreign(['project_id'], 'fk_task_proj_id')->references(['id'])->on('tasks_project');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks_task', function (Blueprint $table) {
            $table->dropForeign('fk_task_made_by');
            $table->dropForeign('fk_task_proj_id');
        });
    }
};
