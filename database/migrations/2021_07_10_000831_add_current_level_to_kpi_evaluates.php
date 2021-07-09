<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrentLevelToKpiEvaluates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kpi_evaluates', function (Blueprint $table) {
            $table->integer('current_level')->nullable()->unsigned()->after('template_id')->comment('id of kpi_users_approve แสดงระดับปัจจุบัน');
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
            $table->dropColumn('current_level');
        });
    }
}
