<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProvidingAtToLegalContracts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('legal_contracts', function (Blueprint $table) {
            $table->dateTime('providing_at')->nullable()->comment('วันที่ Legal Providing');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('legal_contracts', function (Blueprint $table) {
            $table->dropColumn('providing_at');
        });
    }
}
