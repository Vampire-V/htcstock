<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuleTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_rule_templates', function (Blueprint $table) {
            $table->id()->comment('id rule_templates');
            $table->foreignId('template_id')->nullable()->constrained('kpi_templates')->comment('Id ของ Template');
            $table->foreignId('rule_id')->nullable()->constrained('kpi_rules')->comment('Id ของ Rule');
            $table->decimal('weight', 5, 2)->nullable()->comment('ค่าถ่วงน้ำหนักของ Rule');
            $table->decimal('weight_category', 5, 2)->nullable()->comment('ค่าถ่วงน้ำหนักของ Category');
            $table->integer('parent_rule_template_id')->nullable()->comment('id rule_templates ที่เชื่อมโยงกัน');
            $table->string('field',10)->nullable()->comment('เชื่อมโยงกับ Target หรือ Actual');
            $table->decimal('target_config', 13, 2)->nullable()->comment('ค่าเป้าหมายตั้งต้น');
            $table->decimal('base_line', 13, 2)->nullable()->comment('ค่า Base Line');
            $table->decimal('max_result', 13, 2)->nullable()->comment('ผลลัพธ์สูงสุดที่เป็นไปได้');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kpi_rule_templates');
    }
}
