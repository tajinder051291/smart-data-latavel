<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentDetailsAndPaymentImageToOrderInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_invoices', function (Blueprint $table) {
            $table->string('payment_details',1000)->after('bank_details')->nullable();
            $table->string('payment_attachment',1000)->after('bank_details')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_invoices', function (Blueprint $table) {
            //
        });
    }
}
