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
        Schema::table('submitted_tickets', function (Blueprint $table) {
            $table->foreign(['department_id'], 'fk_st_dept_id')->references(['id'])->on('departaments');
            $table->foreign(['user_id'], 'fk_st_user_id')->references(['id'])->on('users');
            $table->foreign(['registered_by_user_id'], 'fk_st_reg_user_id')->references(['id'])->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submitted_tickets', function (Blueprint $table) {
            $table->dropForeign('fk_st_dept_id');
            $table->dropForeign('fk_st_user_id');
            $table->dropForeign('fk_st_reg_user_id');
        });
    }
};
