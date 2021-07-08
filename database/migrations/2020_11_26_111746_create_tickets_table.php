<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('subject',1000)->nullable();
            $table->string('description',5000)->nullable();
            $table->string('images',5000)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->integer('user_role');
            $table->boolean('have_comments')->default(0);
            $table->boolean('is_read')->default(0);
            $table->boolean('is_active')->default(1);
            $table->boolean('is_admin')->default(0);
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
        Schema::dropIfExists('tickets');
    }
}
