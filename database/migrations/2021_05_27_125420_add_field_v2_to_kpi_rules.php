<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldV2ToKpiRules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kpi_rules', function (Blueprint $table) {
            $table->decimal('base_line', 13, 2)->default(0)->comment('ค่า Base Line');
            $table->decimal('max', 13, 2)->default(0)->comment('ค่า Max');
            $table->text('desc_m')->nullable()->comment('calculation machanism');
            $table->foreignId('department_id')->nullable()->constrained('departments')->comment('Data Sources');
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
            $table->dropIndex('department_id');
            $table->dropColumn(['base_line', 'max', 'desc_m', 'department_id']);
        });
    }
}
