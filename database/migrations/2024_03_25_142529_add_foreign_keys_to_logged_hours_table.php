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
        Schema::table('logged_hours', function (Blueprint $table) {
            $table->foreign(['user_id'], 'fk_lh_user_id')->references(['id'])->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logged_hours', function (Blueprint $table) {
            $table->dropForeign('fk_lh_user_id');
        });
    }
};
