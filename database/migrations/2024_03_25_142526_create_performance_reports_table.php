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
        Schema::create('performance_reports', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('fk_perf_user_id');
            $table->string('employee_name', 100);
            $table->integer('project_id')->index('fk_perf_proj_id');
            $table->text('rating_text');
            $table->tinyInteger('rating');
            $table->integer('year');
            $table->string('month', 100);
            $table->boolean('soft_deleted')->default(false);
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
        Schema::dropIfExists('performance_reports');
    }
};
