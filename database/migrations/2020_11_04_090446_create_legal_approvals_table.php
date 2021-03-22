<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegalApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legal_approvals', function (Blueprint $table) {
            $table->id();
            $table->integer('levels');
            $table->foreignId('user_id')->nullable()->constrained('users')->comment('Id ของ users');
            $table->foreignId('department_id')->nullable()->constrained('departments')->comment('Id ของ departments');
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
        Schema::dropIfExists('legal_approvals');
    }
}
