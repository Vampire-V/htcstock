<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationRuleToKpiRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kpi_rules', function (Blueprint $table) {
            $table->unsignedInteger('parent')->nullable()->after('department_id')->comment('id ของ rule ที่ต้องคำนวณ value กัน');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kpi_rules', function (Blueprint $table) {
            $table->dropColumn('parent');
        });
    }
}
