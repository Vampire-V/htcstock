<?php

use App\Enum\ApprovalEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegalApprovalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legal_approval_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->nullable()->constrained('legal_contracts')->comment('Id ของ legal_contracts');
            $table->foreignId('user_id')->nullable()->constrained('users')->comment('Id ของ users');
            $table->integer('levels');
            $table->enum('status', ApprovalEnum::$types)->nullable();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('legal_approval_details');
    }
}
