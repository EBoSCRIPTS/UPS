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
        Schema::create('tasks_project', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name', 100);
            $table->integer('department_id')->index('fk_dept_id');
            $table->integer('leader_user_id')->nullable()->index('fk_projleaduser_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks_project');
    }
};
