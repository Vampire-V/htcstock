<?php

use App\Enum\KPIEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDegreeToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('degree',[KPIEnum::one,KPIEnum::two,KPIEnum::tree])->default(KPIEnum::tree)->after('divisions_id')->comment('ระดับผู้ประเมิณ ระบบ KPI');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('degree');
        });
    }
}
