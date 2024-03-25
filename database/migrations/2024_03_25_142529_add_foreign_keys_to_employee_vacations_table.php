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
        Schema::table('employee_vacations', function (Blueprint $table) {
            $table->foreign(['absence_req_id'], 'fk_emp_abs_id')->references(['id'])->on('req_absence');
            $table->foreign(['user_id'], 'fk_emp_vac_user_id')->references(['id'])->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_vacations', function (Blueprint $table) {
            $table->dropForeign('fk_emp_abs_id');
            $table->dropForeign('fk_emp_vac_user_id');
        });
    }
};
