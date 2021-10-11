<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRuleLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_rule_log', function (Blueprint $table) {
            $table->id();
            $table->string('action')->comment('การกระทำ');
            $table->bigInteger('created_by')->nullable()->comment('ชื่อคนสร้าง');
            $table->ipAddress('ip')->comment('IP ของ user ที่สร้าง log');
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
        Schema::dropIfExists('kpi_rule_log');
    }
}
