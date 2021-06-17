<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpiSettingActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpi_setting_actions', function (Blueprint $table) {
            $table->id();
            $table->enum('slug',['send-to-user','set-actual','approved'])->unique()->comment('status action');
            $table->string('end',2)->comment('วันที่ dead line');
            $table->text('remark')->nullable()->comment('อยากเขียนไรก็เขียนไป');
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
        Schema::dropIfExists('kpi_setting_actions');
    }
}
