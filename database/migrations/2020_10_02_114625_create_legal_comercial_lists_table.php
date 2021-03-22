<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegalComercialListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legal_comercial_lists', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->float('unit_price')->nullable();
            $table->float('discount')->nullable();
            $table->float('amount')->nullable();

            $table->integer('road')->nullable();
            $table->integer('building')->nullable();
            $table->integer('toilet')->nullable();
            $table->integer('canteen')->nullable();
            $table->integer('washing')->nullable();
            $table->integer('water')->nullable();
            $table->integer('mowing')->nullable();
            $table->integer('general')->nullable();
            $table->foreignId('contract_dests_id')->nullable()->constrained('legal_contract_dests')->comment('Id ของ legal_contract_dests');

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
        Schema::dropIfExists('legal_comercial_lists');
    }
}
