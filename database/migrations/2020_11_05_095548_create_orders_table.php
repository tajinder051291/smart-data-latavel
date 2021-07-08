<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {

            $table->bigIncrements('id');

            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('sellers');

            $table->integer('delivery_method')->default(1)->comment('1 = by seller, 2 = by manager');

            $table->boolean('stock_availablity')->default(0)->comment('0 = not available, 1 = available');

            $table->unsignedBigInteger('accepted_by')->nullable();
            $table->string('accepted_by_role')->nullable();

            $table->timestamp('accepted_date')->nullable();

            $table->integer('order_status')->default(0)->comment("0 = inprogress, 1 = accepted, 2 = pickup_added , 3 = pickup_confirmed, 4 = pickup_deposited, 5 = on_hold, 6 = delivered, 7 = cancelled");

            $table->timestamp('due_date')->nullable();

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
