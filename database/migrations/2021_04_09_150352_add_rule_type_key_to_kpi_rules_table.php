<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRuleTypeKeyToKpiRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kpi_rules', function (Blueprint $table) {
            $table->foreignId('kpi_rule_types_id')->nullable()->constrained('kpi_rule_types')->comment('Id ของ kpi_rule_types')->after('calculate_type');
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
            $table->dropForeign('kpi_rule_types_kpi_rule_types_id_foreign');
        });
    }
}
