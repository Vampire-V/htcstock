<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedByToRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kpi_rules', function (Blueprint $table) {
            $table->foreignId('created_by')->after('parent')->comment('id ของ user เพื่อดูว่าใครเพิ่มเข้ามา')->nullable()->constrained('users');
            $table->foreignId('updated_by')->after('created_at')->comment('id ของ user เพื่อดูว่าใครแก้ไข ล่าสุด')->nullable()->constrained('users');
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
            $table->dropForeign('kpi_rules_created_by_foreign');
            $table->dropForeign('kpi_rules_updated_by_foreign');
            $table->dropColumn(['created_by','updated_by']);
        });
    }
}
