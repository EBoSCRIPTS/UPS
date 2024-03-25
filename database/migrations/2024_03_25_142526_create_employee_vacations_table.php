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
        Schema::create('employee_vacations', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('fk_emp_vac_user_id');
            $table->date('date_from');
            $table->date('date_to');
            $table->enum('is_paid', ['0', '1', '', '']);
            $table->integer('absence_req_id')->index('fk_emp_abs_id');
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
        Schema::dropIfExists('employee_vacations');
    }
};
