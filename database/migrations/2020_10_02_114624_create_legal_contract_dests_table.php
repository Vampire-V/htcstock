<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegalContractDestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legal_contract_dests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_type_contract_id')->nullable()->constrained('legal_subtype_contracts')->comment('Id ของ legal_subtype_contracts');
            $table->longText('purchase_order')->nullable();
            $table->longText('quotation')->nullable();
            $table->longText('coparation_sheet')->nullable();
            $table->longText('work_plan')->nullable();
            $table->longText('boq')->nullable();
            $table->longText('drawing')->nullable();
            $table->longText('factory_permission')->nullable();
            $table->longText('waste_permission')->nullable();
            $table->longText('transportation_permission')->nullable();
            $table->longText('vehicle_registration_certificate')->nullable();
            $table->longText('route')->nullable();
            $table->longText('insurance')->nullable();
            $table->longText('driver_license')->nullable();
            $table->longText('doctor_license')->nullable();
            $table->longText('nurse_license')->nullable();
            $table->longText('security_service_certification')->nullable();
            $table->longText('security_guard_license')->nullable();

            $table->foreignId('comercial_term_id')->nullable()->constrained('legal_comercial_terms')->comment('Id ของ legal_comercial_terms');

            $table->foreignId('payment_term_id')->nullable()->constrained('legal_payment_terms')->comment('Id ของ legal_payment_terms');

            $table->foreignId('payment_type_id')->nullable()->constrained('legal_payment_types')->comment('Id ของ legal_payment_types');

            $table->string('value_of_contract', 50)->nullable();
            $table->integer('warranty')->nullable();
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
        Schema::dropIfExists('legal_contract_dests');
    }
}
