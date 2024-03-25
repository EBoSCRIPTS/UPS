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
        Schema::create('submitted_tickets', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id');
            $table->string('ticket_title', 100);
            $table->text('ticket_text');
            $table->integer('department_id')->index('fk_st_dept_id');
            $table->boolean('is_registered')->default(false);
            $table->integer('registered_by_user_id')->nullable()->index('fk_st_reg_user_id');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();

            $table->index(['user_id', 'registered_by_user_id'], 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submitted_tickets');
    }
};
