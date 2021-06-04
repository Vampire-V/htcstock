<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuarterToPeriodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kpi_target_periods', function (Blueprint $table) {
            $table->integer('quarter')->comment('Q1,Q2,Q3,Q4');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kpi_target_periods', function (Blueprint $table) {
            $table->dropColumn('quarter');
        });
    }
}
