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
        Schema::create('logged_hours', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->index('fk_lh_user_id')->comment('goes to user table');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('night_hours');
            $table->text('total_hours');
            $table->date('date');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logged_hours');
    }
};
