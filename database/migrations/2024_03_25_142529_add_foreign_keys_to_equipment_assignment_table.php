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
        Schema::table('equipment_assignment', function (Blueprint $table) {
            $table->foreign(['employee_id'], 'fk_eq_assignment_employee_id')->references(['id'])->on('employee_information');
            $table->foreign(['equipment_id'], 'fk_eq_assignment_equip_item_id')->references(['id'])->on('equipment_items');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipment_assignment', function (Blueprint $table) {
            $table->dropForeign('fk_eq_assignment_employee_id');
            $table->dropForeign('fk_eq_assignment_equip_item_id');
        });
    }
};
