<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSetachToKpiEvaluates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kpi_evaluates', function (Blueprint $table) {
            $table->decimal('ach_kpi', 5, 2)->nullable()->comment('คะแนนของ Ach KPI fixed');
            $table->decimal('ach_key_task', 5, 2)->nullable()->comment('คะแนนของ Ach Key-Task fixed');
            $table->decimal('ach_omg', 5, 2)->nullable()->comment('คะแนนของ Ach OMG fixed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kpi_evaluates', function (Blueprint $table) {
            $table->dropColumn(['ach_kpi', 'ach_key_task', 'ach_omg']);
        });
    }
}
