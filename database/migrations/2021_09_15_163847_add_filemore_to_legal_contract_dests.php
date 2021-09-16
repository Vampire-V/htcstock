<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilemoreToLegalContractDests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('legal_contract_dests', function (Blueprint $table) {
            $table->longText('insurance_policy')->after('coparation_sheet')->nullable();
            $table->longText('cer_of_ownership')->after('insurance_policy')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('legal_contract_dests', function (Blueprint $table) {
            $table->dropColumn(['insurance_policy','cer_of_ownership']);
        });
    }
}
