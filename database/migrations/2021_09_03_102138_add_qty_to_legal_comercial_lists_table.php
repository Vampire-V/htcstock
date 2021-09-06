<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQtyToLegalComercialListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('legal_comercial_lists', function (Blueprint $table) {
            $table->decimal('qty', 10, 2)->default(0)->after('description')->comment('จำนวณ');
            $table->decimal('price', 10, 2)->default(0)->after('unit_price')->comment('ราคา ต่อ ชิ้น');
            
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
            $table->dropColumn(['qty','price']);
        });
    }
}
