<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplateLibariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legal_template_libaries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('ชื่อไฟล์');
            $table->string('name_encryp')->comment('ชื่อไฟล์ หลังอัพโหลด');
            $table->string('version')->nullable()->comment('version file');
            $table->text('uri')->nullable()->comment('path file');
            $table->text('url')->nullable()->comment('link file');
            $table->bigInteger('agreement_id')->nullable()->comment('type id of file');
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
        Schema::dropIfExists('legal_template_libaries');
    }
}
