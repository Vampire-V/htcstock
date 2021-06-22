<?php

use App\Enum\KPIEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuarterCalToKpiRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kpi_rules', function (Blueprint $table) {
            $table->enum('quarter_cal',[KPIEnum::average,KPIEnum::last_month,KPIEnum::sum])->default(KPIEnum::average)->after('calculate_type')->comment('ประเภทการคำนวณ หน้า quarter');
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
            $table->dropColumn('quarter_cal');
        });
    }
}
