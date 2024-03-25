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
        Schema::table('performance_reports', function (Blueprint $table) {
            $table->foreign(['project_id'], 'fk_perf_proj_id')->references(['id'])->on('tasks_project');
            $table->foreign(['user_id'], 'fk_perf_user_id')->references(['id'])->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('performance_reports', function (Blueprint $table) {
            $table->dropForeign('fk_perf_proj_id');
            $table->dropForeign('fk_perf_user_id');
        });
    }
};
