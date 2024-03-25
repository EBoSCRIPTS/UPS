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
        Schema::table('tasks_participants', function (Blueprint $table) {
            $table->foreign(['employee_id'], 'fk_tasks_participants_emp_id')->references(['id'])->on('employee_information');
            $table->foreign(['project_id'], 'fk_tasks_participants_project_id')->references(['id'])->on('tasks_project');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks_participants', function (Blueprint $table) {
            $table->dropForeign('fk_tasks_participants_emp_id');
            $table->dropForeign('fk_tasks_participants_project_id');
        });
    }
};
