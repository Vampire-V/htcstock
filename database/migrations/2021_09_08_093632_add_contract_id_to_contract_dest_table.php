<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContractIdToContractDestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('legal_contract_dests', function (Blueprint $table) {
            $table->bigInteger('contract_id')->nullable()->comment('id legal contract');
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
            $table->dropColumn('contract_id');
        });
    }
}
