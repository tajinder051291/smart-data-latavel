<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            
            $table->bigIncrements('id');

            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('mobile_brands');

            $table->unsignedBigInteger('model_id');
            $table->foreign('model_id')->references('id')->on('mobile_models');

            $table->integer('quantity');
            $table->float('price');

            $table->integer('negotiated_quantity')->nullable();
            $table->float('negotiated_price')->nullable();


            $table->unsignedBigInteger('negotiated_by')->nullable();
            $table->string('negotiated_by_role')->nullable();

            $table->timestamp('negotiation_date')->nullable();

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
        Schema::dropIfExists('order_items');
    }
}
