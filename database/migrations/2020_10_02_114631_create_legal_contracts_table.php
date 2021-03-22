<?php

use App\Enum\ContractEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegalContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legal_contracts', function (Blueprint $table) {
            // header
            $table->id();
            $table->enum('status', [ContractEnum::R, ContractEnum::CK, ContractEnum::P, ContractEnum::CP])->default(ContractEnum::R);
            $table->foreignId('action_id')->nullable()->constrained('legal_actions')->comment('Id ของ legal_actions');
            $table->foreignId('agreement_id')->nullable()->constrained('legal_agreements')->comment('Id ของ legal_agreements');
            $table->string('company_name')->nullable();
            $table->longText('company_cer')->nullable();
            $table->string('representative')->nullable();
            $table->longText('representative_cer')->nullable();
            $table->text('address')->nullable();
            // body
            $table->foreignId('contract_dest_id')->nullable()->constrained('legal_contract_dests')->comment('Id ของ legal_contract_dests');
            // footer
            $table->foreignId('requestor_by')->nullable()->constrained('users')->comment('Id ของ users');
            $table->foreignId('checked_by')->nullable()->constrained('users')->comment('Id ของ users');
            $table->foreignId('created_by')->nullable()->constrained('users')->comment('Id ของ users');
            $table->boolean('trash')->default(false);
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
        Schema::dropIfExists('legal_contracts');
    }
}
