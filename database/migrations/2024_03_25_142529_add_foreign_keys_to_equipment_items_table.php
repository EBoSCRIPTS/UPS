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
        Schema::table('equipment_items', function (Blueprint $table) {
            $table->foreign(['type_id'], 'fk_type_id')->references(['id'])->on('equipment_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('equipment_items', function (Blueprint $table) {
            $table->dropForeign('fk_type_id');
        });
    }
};
