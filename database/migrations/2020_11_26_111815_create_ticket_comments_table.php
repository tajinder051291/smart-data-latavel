<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('comment',5000)->nullable();
            $table->string('images',5000)->nullable();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('commented_by');
            $table->integer('user_role');
            $table->boolean('is_read')->default(0);
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
        Schema::dropIfExists('ticket_comments');
    }
}
