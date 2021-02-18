<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_evaluates', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable()->comment('Id ของ users');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('period_id')->nullable()->constrained('kpi_target_periods')->comment('Id ของ kpi_target_periods');
            $table->unsignedInteger('head_id')->nullable()->comment('Id ของ users head');
            $table->foreign('head_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('status', 255)->nullable()->comment('สถานะ การประเมิน');
            
            $table->foreignId('rule_template_id')->nullable()->constrained('kpi_rule_templates')->comment('Id ของ kpi_rule_templates');

            $table->integer('main_rule_id')->nullable()->comment('Id ของ Main Rule');
            $table->integer('main_rule_condition_1_min')->nullable()->comment('ค่า Main Rule Condition 1 ต่ำสุด');
            $table->integer('main_rule_condition_1_max')->nullable()->comment('ค่า Main Rule Condition 1 สูงสุด');
            $table->integer('main_rule_condition_2_min')->nullable()->comment('ค่า Main Rule Condition 2 ต่ำสุด');
            $table->integer('main_rule_condition_2_max')->nullable()->comment('ค่า Main Rule Condition 2 สูงสุด');
            $table->integer('main_rule_condition_3_min')->nullable()->comment('ค่า Main Rule Condition 3 ต่ำสุด');
            $table->integer('main_rule_condition_3_max')->nullable()->comment('ค่า Main Rule Condition 3 สูงสุด');
            $table->decimal('total_weight_kpi', 5, 2)->nullable()->comment('คะแนนของ KPI');
            $table->decimal('total_weight_key_task', 5, 2)->nullable()->comment('คะแนนของ Key Task');
            $table->decimal('total_weight_key_omg', 5, 2)->nullable()->comment('คะแนนของ OMG');
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
        Schema::dropIfExists('kpi_evaluates');
    }
}
