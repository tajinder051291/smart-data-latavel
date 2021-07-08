<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_no')->nullable();
            $table->integer('country_code')->default(91);
            $table->integer('user_role')->nullable();
            $table->integer('country')->default(101);
            $table->integer('state')->nullable();
            $table->text('image')->nullable();
            $table->tinyInteger('app_notifications')->default(1);
            $table->tinyInteger('is_active')->default(1);
            $table->integer('OTP')->nullable();
            $table->string('expiry_time')->nullable();
            $table->softDeletes();  
            $table->tinyInteger('phone_verified')->default(0);
            $table->tinyInteger('email_verified')->default(0);
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
            //
        });
    }
}
