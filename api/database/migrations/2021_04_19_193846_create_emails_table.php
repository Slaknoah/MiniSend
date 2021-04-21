<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('sender_name')->nullable();
            $table->string('sender_email');
            $table->json('cc')->nullable();
            $table->json('bcc')->nullable();
            $table->json('reply_to')->nullable();
            $table->string('subject');
            $table->string('status')->default('posted');
            $table->unsignedBigInteger('added_by');
            $table->foreign('added_by')->references('id')->on('users');
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
        Schema::dropIfExists('emails');
    }
}
