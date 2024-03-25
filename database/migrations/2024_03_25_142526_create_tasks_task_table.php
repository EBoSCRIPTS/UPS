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
        Schema::create('tasks_task', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('title');
            $table->text('description');
            $table->integer('made_by')->index('fk_made_by');
            $table->integer('assigned_to')->nullable();
            $table->integer('status_key')->comment('key for retrieving values from statuses names json');
            $table->string('priority', 50);
            $table->decimal('task_points', 4, 1)->nullable();
            $table->string('label', 50)->nullable();
            $table->boolean('is_draft')->nullable()->default(false);
            $table->integer('project_id')->index('fk_project_id');
            $table->boolean('is_completed')->default(false);
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks_task');
    }
};
