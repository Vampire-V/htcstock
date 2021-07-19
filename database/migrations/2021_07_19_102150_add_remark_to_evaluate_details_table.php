<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemarkToEvaluateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kpi_evaluate_details', function (Blueprint $table) {
            $table->text('remark')->after('max_result')->nullable()->comment('remark data');
            $table->integer('created_by')->after('remark')->nullable()->comment('ไอดี คนสร้าง');
            $table->integer('updated_by')->after('remark')->nullable()->comment('ไอดี คนแก้ไข');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kpi_evaluate_details', function (Blueprint $table) {
            $table->dropColumn(['remark', 'created_by', 'updated_by']);
        });
    }
}
