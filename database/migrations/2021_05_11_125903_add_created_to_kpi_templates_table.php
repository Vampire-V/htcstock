<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedToKpiTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kpi_templates', function (Blueprint $table) {
            $table->foreignId('user_created')->nullable()->constrained('users')->comment('Id ของ user ที่สร้าง template');
            $table->decimal('weight_kpi', 13, 2)->nullable()->comment('น้ำหนัก kpi');
            $table->decimal('weight_key_task', 13, 2)->nullable()->comment('น้ำหนัก key-task');
            $table->decimal('weight_omg', 13, 2)->nullable()->comment('น้ำหนัก omg');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kpi_templates', function (Blueprint $table) {
            $table->dropIndex('user_created');
            $table->dropColumn(['user_created', 'weight_kpi', 'weight_key_task', 'weight_omg']);
        });
    }
}
