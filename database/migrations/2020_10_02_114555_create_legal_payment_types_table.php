<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegalPaymentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legal_payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('agreement_id')->nullable()->constrained('legal_agreements')->comment('Id ของ legal_agreements');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('legal_payment_types');
    }
}
