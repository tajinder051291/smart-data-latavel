<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddedByAndAddedByRoleColumnsToDeliveryPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_partners', function (Blueprint $table) {
            $table->unsignedBigInteger('added_by')->nullable();
            $table->integer('added_by_role')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_partners', function (Blueprint $table) {
            //
        });
    }
}
