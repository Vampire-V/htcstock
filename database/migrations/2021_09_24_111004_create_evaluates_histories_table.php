<?php

use App\Enum\KPIEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluatesHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_evaluates_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('evaluate_id')->nullable()->comment('id kpi_evaluates');
            $table->enum('status',KPIEnum::$status)->nullable()->comment('สถานะ การประเมิน
            New:	admin create form
            Ready:	user เข้ามาใส่ actual เมื่อกด save สถานะจะเปลี่ยนเป็น  Draft
            Draft:	user ยังเข้ามาแก้ไข  Actual ได้ จนกว่าจะกด submit to manager เมื่อกดแล้วจะเป็น  On Process
            On Process: อยู่ในขั้นตอน Approve
            Submitted:	Manager , Admin เข้ามา review or edit ของ User ที่อยู่ภายใต้การดูแลของตนเอง และกด Approve จะเปลี่ยนเป็น  Approved
            Approved:	สามารถดูได้อย่างเดียว แก้ไขไม่ได้');
            $table->text('comment')->nullable();
            $table->integer('current_level')->nullable()->comment('id of kpi_users_approve แสดงระดับปัจจุบัน');
            $table->integer('next_level')->nullable()->comment('id of kpi_users_approve เมื่อมีการอนุมัติ จะส่งให้ id นี้ต่อไป');
            $table->bigInteger('created_by')->comment('Id ของ user ที่สร้าง history');
            $table->ipAddress('ip')->comment('IP ของ user ที่สร้าง history');
            $table->macAddress('device')->comment('Mac Address ของ user ที่สร้าง history');
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
        Schema::dropIfExists('kpi_evaluates_history');
    }
}
