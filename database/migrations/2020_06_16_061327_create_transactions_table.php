<?php

use App\Enum\TransactionTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('access_id');
            $table->integer('qty');
            $table->enum('trans_type', [
                TransactionTypeEnum::B, 
                TransactionTypeEnum::CB, 
                TransactionTypeEnum::L, 
                TransactionTypeEnum::CL, 
                TransactionTypeEnum::R, 
                TransactionTypeEnum::CR
                ]);
            $table->integer('trans_by');
            $table->string('trans_desc')->nullable();
            $table->string('ir_no')->nullable();
            $table->dateTime('ir_date')->nullable();
            $table->string('po_no')->nullable();
            $table->string('invoice_no')->nullable();
            $table->double('unit_cost')->nullable();
            $table->string('vendor_id')->nullable();
            $table->string('ref_no')->nullable();
            $table->integer('created_by');
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
        Schema::dropIfExists('transactions');
    }
}
