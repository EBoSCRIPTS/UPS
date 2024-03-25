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
        Schema::create('logged_hours_submitted', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('fk_lhs_user_id')->comment('refers to user table id');
            $table->integer('total_hours');
            $table->integer('night_hours')->nullable();
            $table->integer('overtime_hours')->nullable();
            $table->string('month_name', 30);
            $table->dateTime('created_at')->useCurrent();
            $table->enum('is_confirmed', ['0', '1', '', ''])->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logged_hours_submitted');
    }
};
