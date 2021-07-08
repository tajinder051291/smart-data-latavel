<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWarehouseReceivedQualityAndWarehouseStocksWithIssueInWarehouseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse_details', function (Blueprint $table) {
            $table->integer('warehouse_received_quality')->default(1);
            $table->integer('warehouse_stocks_with_issue')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse_details', function (Blueprint $table) {
            //
        });
    }
}
