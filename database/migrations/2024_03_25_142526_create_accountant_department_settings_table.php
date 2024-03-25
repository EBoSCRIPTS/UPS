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
        Schema::create('accountant_department_settings', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('department_id')->index('fK_dept_settings_id')->comment('refers to departments id ');
            $table->string('tax_name', 500);
            $table->decimal('tax_rate', 10);
            $table->decimal('tax_salary_from', 10);
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
        Schema::dropIfExists('accountant_department_settings');
    }
};
