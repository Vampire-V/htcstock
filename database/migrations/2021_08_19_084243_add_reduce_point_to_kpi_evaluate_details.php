<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReducePointToKpiEvaluateDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kpi_evaluates', function (Blueprint $table) {
            $table->decimal('kpi_reduce', 10, 2)->default(0)->after('comment')->comment('หักคะแนน หากให้ข้อมูลเกินเวลา');
            $table->decimal('key_task_reduce', 10, 2)->default(0)->after('comment')->comment('หักคะแนน หากให้ข้อมูลเกินเวลา');
            $table->decimal('omg_reduce', 10, 2)->default(0)->after('comment')->comment('หักคะแนน หากให้ข้อมูลเกินเวลา');
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
            $table->dropColumn(['kpi_reduce', 'key_task_reduce', 'omg_reduce']);
        });
    }
}
