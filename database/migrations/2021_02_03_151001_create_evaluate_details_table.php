<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_evaluate_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluate_id')->nullable()->constrained('kpi_evaluates')->onDelete('cascade')->comment('Id ของ evaluates');
            $table->unsignedInteger('rule_id')->nullable()->comment('Id ของ rule ได้มาจาก template');
            $table->decimal('target', 13, 2)->default(0)->comment('ค่า target');
            $table->decimal('actual', 13, 2)->default(0)->comment('ค่า actual');
            $table->decimal('weight', 5, 2)->default(0)->comment('ค่าถ่วงน้ำหนัก');
            $table->decimal('weight_category', 5, 2)->default(0)->comment('ค่าถ่วงน้ำหนักของ Category');
            $table->decimal('base_line', 13, 2)->default(0)->comment('ค่า Base Line');
            $table->decimal('max_result', 13, 2)->default(0)->comment('ผลลัพธ์สูงสุด');
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
        Schema::dropIfExists('kpi_evaluate_details');
    }
}
