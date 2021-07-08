<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sellers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->text('password')->nullable();
            $table->string('phone_number')->nullable();
            $table->integer('counry_code')->default('91');
            $table->string('aadhaar_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('gst_number')->nullable();
            $table->text('address')->nullable();
            $table->string('pincode')->nullable();
            $table->tinyInteger('is_verified')->default(0);
            $table->tinyInteger('is_active')->default(1);
            $table->text('aadhaar_image')->nullable();
            $table->text('pan_image')->nullable();
            $table->text('gst_image')->nullable();
            $table->text('cheque_image')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('sellers');
    }
}
