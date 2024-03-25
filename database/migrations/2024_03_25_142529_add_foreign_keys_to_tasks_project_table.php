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
        Schema::table('tasks_project', function (Blueprint $table) {
            $table->foreign(['department_id'], 'fk_proj_dept_id')->references(['id'])->on('departaments');
            $table->foreign(['leader_user_id'], 'fk_projleaduser_id')->references(['id'])->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks_project', function (Blueprint $table) {
            $table->dropForeign('fk_proj_dept_id');
            $table->dropForeign('fk_projleaduser_id');
        });
    }
};
