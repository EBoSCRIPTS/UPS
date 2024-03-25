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
        Schema::table('accountant_department_settings', function (Blueprint $table) {
            $table->foreign(['department_id'], 'fK_dept_settings_id')->references(['id'])->on('departaments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accountant_department_settings', function (Blueprint $table) {
            $table->dropForeign('fK_dept_settings_id');
        });
    }
};
