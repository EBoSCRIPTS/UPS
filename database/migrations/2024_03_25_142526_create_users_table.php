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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email')->unique('email');
            $table->string('phone_number', 15)->unique('phone_number');
            $table->string('password');
            $table->dateTime('created_at')->useCurrent();
            $table->dateTime('updated_at')->useCurrent();
            $table->unsignedInteger('role_id')->index('fk_user_role_id');
            $table->enum('is_writer', ['0', '1', '', ''])->default('0');
            $table->integer('soft_deleted')->nullable()->default(0);
            $table->text('profile_picture')->default('uploads/default_pfp.png');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
