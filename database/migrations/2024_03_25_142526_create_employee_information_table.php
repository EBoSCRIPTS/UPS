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
        Schema::create('employee_information', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('user_id')->comment('refers to user table');
            $table->integer('department_id')->index('fk_dept_id')->comment('refers to departments table');
            $table->decimal('hour_pay', 10)->nullable();
            $table->decimal('salary', 10)->nullable();
            $table->integer('weekly_hours');
            $table->text('position');
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account_name', 100)->nullable();
            $table->string('bank_account', 100)->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_information');
    }
};
