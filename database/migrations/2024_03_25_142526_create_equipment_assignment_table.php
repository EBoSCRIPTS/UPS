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
        Schema::create('equipment_assignment', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('equipment_id')->index('fk_eq_assignment_equip_item_id');
            $table->integer('employee_id')->index('fk_eq_assignment_employee_id');
            $table->date('date_given');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_assignment');
    }
};
