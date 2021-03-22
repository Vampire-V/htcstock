<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupDivisionIdToDivisions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->string('group_division_id')->nullable();
            $table->foreign('group_division_id')->references('GDivisionID')->on('group_divisions')->comment('Id ของ group_divisions')->after('division_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('divisions', function (Blueprint $table) {
            $table->dropForeign('divisions_group_division_id_foreign');
            $table->dropColumn('group_division_id');
        });
    }
}
