<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_en')->nullable()->comment('ชื่อภาษาอังกฤษ');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username');
            $table->string('password');
            $table->foreignId('department_id')->nullable()->constrained('departments')->comment('Id ของ Departments');
            $table->string('locale')->nullable();
            $table->string('phone')->nullable();
            
            $table->string('head_id')->nullable()->comment('id หัวหน้า');
            $table->string('incentive_type', 255)->nullable()->comment('ประเภทแรงจูงใจ Quarter หรือ Month');
            
            $table->foreignId('positions_id')->nullable()->constrained('positions')->comment('Id ของ positions');
            $table->foreignId('divisions_id')->nullable()->constrained('divisions')->comment('Id ของ divisions');
            
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
