<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHODReducePointToKpievaluate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kpi_evaluates', function (Blueprint $table) {
            $table->decimal('kpi_reduce_hod', 10, 2)->default(0)->after('comment')->comment('HOD หักคะแนน');
            $table->decimal('key_task_reduce_hod', 10, 2)->default(0)->after('comment')->comment('HOD หักคะแนน');
            $table->decimal('omg_reduce_hod', 10, 2)->default(0)->after('comment')->comment('HOD หักคะแนน');
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
            $table->dropColumn(['kpi_reduce_hod', 'key_task_reduce_hod', 'omg_reduce_hod']);
        });
    }
}
