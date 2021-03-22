<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegalPaymentTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legal_payment_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_type_id')->nullable()->constrained('legal_payment_types')->comment('Id ของ legal_payment_types');
            $table->text('detail_payment_list')->nullable();
            $table->float('monthly')->nullable();
            $table->float('route_change')->nullable();
            $table->float('payment_ot')->nullable();
            $table->float('holiday_pay')->nullable();
            $table->float('ot_driver')->nullable();
            $table->string('other_expense')->nullable();
            $table->text('price_of_service')->nullable();
            $table->text('detail_payment_term')->nullable();
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
        Schema::dropIfExists('legal_payment_terms');
    }
}
