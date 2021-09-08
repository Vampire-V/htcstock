<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContractIdToComercialListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('legal_comercial_lists', function (Blueprint $table) {
            $table->bigInteger('contract_id')->nullable()->comment('id legal_contracts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('legal_comercial_lists', function (Blueprint $table) {
            $table->dropForeign('legal_comercial_lists_contract_dests_id_foreign');
            $table->dropColumn('contract_dests_id');
        });
    }
}
