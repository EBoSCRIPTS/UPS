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
        Schema::create('tasks_participants', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('project_id')->index('fk_tasks_participants_project_id');
            $table->integer('employee_id')->index('fk_tasks_participants_emp_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks_participants');
    }
};
