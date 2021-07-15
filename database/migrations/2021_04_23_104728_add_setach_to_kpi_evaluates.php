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
            $table->decimal('cal_kpi', 5, 2)->nullable()->comment('คะแนนของ Cal KPI fixed');
            $table->decimal('cal_key_task', 5, 2)->nullable()->comment('คะแนนของ Cal Key-Task fixed');
            $table->decimal('cal_omg', 5, 2)->nullable()->comment('คะแนนของ Cal OMG fixed');
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
            $table->dropColumn(['cal_kpi', 'cal_key_task', 'cal_omg']);
        });
    }
}
