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
        Schema::table('accountant_fulfilled_payslips', function (Blueprint $table) {
            $table->foreign(['employee_id'], 'fk_acot_emp_payslip_id')->references(['id'])->on('employee_information');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accountant_fulfilled_payslips', function (Blueprint $table) {
            $table->dropForeign('fk_acot_emp_payslip_id');
        });
    }
};
