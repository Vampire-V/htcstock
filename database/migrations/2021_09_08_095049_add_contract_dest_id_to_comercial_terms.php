<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContractDestIdToComercialTerms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('legal_comercial_terms', function (Blueprint $table) {
            $table->bigInteger('contract_dest_id')->nullable()->comment('id legal_contract_dests');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('legal_comercial_terms', function (Blueprint $table) {
            //
        });

        Schema::table('legal_contract_dests', function (Blueprint $table) {
            $table->dropForeign('legal_contract_dests_comercial_term_id_foreign');
            $table->dropColumn('comercial_term_id');
        });
    }
}
