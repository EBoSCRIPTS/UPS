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
        Schema::create('accountant_fulfilled_payslips', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('employee_id')->index('fk_acot_emp_payslip_id');
            $table->integer('department_id');
            $table->integer('loghours_submitted_id');
            $table->string('month', 100);
            $table->year('year');
            $table->integer('fulfilled_by')->comment('user_id references to user table ');
            $table->text('payslip_file');
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
        Schema::dropIfExists('accountant_fulfilled_payslips');
    }
};
