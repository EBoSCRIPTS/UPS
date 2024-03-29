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
        Schema::table('req_absence', function (Blueprint $table) {
            $table->foreign(['approver_id'], 'fk_absence_approver_id')->references(['id'])->on('users');
            $table->foreign(['user_id'], 'fk_users_id')->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('req_absence', function (Blueprint $table) {
            $table->dropForeign('fk_absence_approver_id');
            $table->dropForeign('fk_users_id');
        });
    }
};
