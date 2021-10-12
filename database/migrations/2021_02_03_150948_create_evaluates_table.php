<?php

use App\Enum\KPIEnum;
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
            $table->foreignId('user_id')->nullable()->constrained('users')->comment('Id ของ users');
            $table->foreignId('period_id')->nullable()->constrained('kpi_target_periods')->comment('Id ของ kpi_target_periods');
            $table->foreignId('head_id')->nullable()->constrained('users')->comment('Id ของ users head');
            $table->enum('status',KPIEnum::$status)->nullable()->comment('สถานะ การประเมิน
            New:	admin create form
            Ready:	user เข้ามาใส่ actual เมื่อกด save สถานะจะเปลี่ยนเป็น  Draft
            Draft:	user ยังเข้ามาแก้ไข  Actual ได้ จนกว่าจะกด submit to manager เมื่อกดแล้วจะเป็น  Submitted
            Submitted:	Manager , Admin เข้ามา review or edit ของ User ที่อยู่ภายใต้การดูแลของตนเอง และกด Approve จะเปลี่ยนเป็น  Approved
            Approved:	สามารถดูได้อย่างเดียว แก้ไขไม่ได้');

            $table->foreignId('template_id')->nullable()->constrained('kpi_templates')->comment('Id ของ kpi_templates');

            $table->integer('main_rule_id')->nullable()->comment('Id ของ Main Rule');
            $table->integer('main_rule_condition_1_min')->nullable()->comment('ค่า Main Rule Condition 1 ต่ำสุด');
            $table->integer('main_rule_condition_1_max')->nullable()->comment('ค่า Main Rule Condition 1 สูงสุด');
            $table->integer('main_rule_condition_2_min')->nullable()->comment('ค่า Main Rule Condition 2 ต่ำสุด');
            $table->integer('main_rule_condition_2_max')->nullable()->comment('ค่า Main Rule Condition 2 สูงสุด');
            $table->integer('main_rule_condition_3_min')->nullable()->comment('ค่า Main Rule Condition 3 ต่ำสุด');
            $table->integer('main_rule_condition_3_max')->nullable()->comment('ค่า Main Rule Condition 3 สูงสุด');
            $table->decimal('total_weight_kpi', 5, 2)->nullable()->comment('คะแนนของ KPI');
            $table->decimal('total_weight_key_task', 5, 2)->nullable()->comment('คะแนนของ Key Task');
            $table->decimal('total_weight_omg', 5, 2)->nullable()->comment('คะแนนของ OMG');
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
