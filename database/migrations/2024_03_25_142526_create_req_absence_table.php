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
        Schema::create('req_absence', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('fk_users_id')->comment('to `users` table');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('duration');
            $table->text('type');
            $table->text('reason');
            $table->text('status')->default('Sent');
            $table->text('comment')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->binary('attachment')->nullable();
            $table->integer('approver_id')->nullable()->index('fk_absence_approver_id');
            $table->dateTime('date_approved')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('req_absence');
    }
};
